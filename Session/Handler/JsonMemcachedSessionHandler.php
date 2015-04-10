<?php

namespace Wucdbm\Bundle\WucdbmBundle\Session\Handler;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\MemcachedStorage;

class JsonMemcachedSessionHandler extends ContainerAware implements \SessionHandlerInterface {

    /**
     * @var string
     */
    protected $prefix = 'session';

    /**
     * @var int
     */
    protected $ttl = 0;

    /**
     * @var MemcachedStorage
     */
    protected $memcached;

    public function __construct(MemcachedStorage $memcached, ContainerInterface $container, $prefix = 'session', $ttl = 3600) {
        $this->memcached = $memcached;
        $this->setContainer($container);
        $this->prefix = $prefix;
        $this->ttl    = $ttl;

//        $memcached->setOption(\Memcached::OPT_COMPRESSION, false);
//        $this->memcached = $memcached;
//        $this->doctrine  = $doctrine;
//        $this->container = $container;
//        session_set_save_handler(
//            array($this, "open"),
//            array($this, "close"),
//            array($this, "read"),
//            array($this, "write"),
//            array($this, "destroy"),
//            array($this, "gc")
//        );
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return UserInterface
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    public function getUser() {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!($user = $user = $token->getUser() instanceof UserInterface)) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    public function getKey($id) {
        return $this->memcached->generateKey($this->prefix, $id);
    }


    /**
     * Destructor
     */
    public function __destruct() {
        session_write_close();
    }

    /**
     * Open the session handler, set the lifetime ot session.gc_maxlifetime
     * @return boolean True if everything succeed
     */
    public function open($savePath, $sessionName) {
//        $this->prefix = ini_get('session.gc_maxlifetime');
        return true;
    }

    /**
     * Read the id
     * @param string $id The SESSID to search for
     * @return string The session saved previously
     */
    public function read($id) {
        $tmp      = $_SESSION;
        $key      = $this->getKey($id);
        $_SESSION = json_decode($this->memcached->get($key, false, ''), true);

        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            $roles = array();
            /** @var RoleInterface $role */
            foreach ($user->getRoles() as $role) {
                $roles[] = $role->getRole();
            }
            $userData         = array(
                'username' => $user->getUsername(),
                'roles'    => $roles
            );
            $_SESSION['user'] = $userData;
        }

//        if (isset($_SESSION['_sf2_attributes']['_security_musix_area'])) {
//            $security_token = unserialize($_SESSION['_sf2_attributes']['_security_musix_area']);
//            $user_id        = $security_token->getUser()->getId();
//            $em             = $this->doctrine->getManager();
//            $user           = $em->getRepository("\Users\Entity\User")->findOneById($user_id);
//            $roles          = $security_token->getRoles();
//            $roles_array    = array();
//            if (!empty($roles)) {
//                foreach ($roles as $r) {
//                    $roles_array[] = array(
//                        'id'   => $r->getId(),
//                        'role' => $r->getRole(),
//                        'name' => $r->getName()
//                    );
//                }
//            }
//            $userData = array(
//                'id'       => $user->getId(),
//                'username' => $user->getUsername(),
//                'roles'    => $roles_array
//            );
//
//            $_SESSION['user'] = $userData;
//        }

        if (isset($_SESSION) && !empty($_SESSION) && $_SESSION != null) {
            $new_data = session_encode();
            $_SESSION = $tmp;
            return $new_data;
        }
        return '';
    }

    /**
     * Write the session data, convert to json before storing
     * @param string $id The SESSID to save
     * @param string $data The data to store, already serialized by PHP
     * @return boolean True if memcached was able to write the session data
     */
    public function write($id, $data) {
        $tmp = $_SESSION;
        session_decode($data);
        $newData  = $_SESSION;
        $_SESSION = $tmp;
        $key      = $this->getKey($id);
        return $this->memcached->set($key, json_encode($newData), $this->ttl);
    }

    /**
     * Delete object in session
     * @param string $id The SESSID to delete
     * @return boolean True if memcached was able delete session data
     */
    public function destroy($id) {
        $key = $this->getKey($id);
        return $this->memcached->remove($key);
    }

    /**
     * Close gc
     * @return boolean Always true
     */
    public function gc($lifetime) {
        return true;
    }

    /**
     * Close session
     * @return boolean Always true
     */
    public function close() {
        return true;
    }
}