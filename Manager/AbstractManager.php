<?php

namespace Wucdbm\Bundle\WucdbmBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;

class AbstractManager extends ContainerAware {

    protected $cache = array();

    protected function setCache($key, $id, $value) {
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = array();
        }
        $this->cache[$key][$id] = $value;
        return true;
    }

    protected function getCache($key, $id) {
        if (!$this->hasCache($key, $id)) {
            return null;
        }
        return $this->cache[$key][$id];
    }

    protected function hasCache($key, $id) {
        if (!$this->hasCacheKey($key)) {
            return false;
        }
        return isset($this->cache[$key][$id]);
    }

    protected function hasCacheKey($key) {
        return isset($this->cache[$key]);
    }

    protected function deleteCache($key, $id) {
        unset($this->cache[$key][$id]);
    }

    protected function deleteCacheKey($key) {
        unset($this->cache[$key]);
    }

    /**
     * Shortcut for $doctrine->getManager
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getLoggedUser() {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type The built type of the form
     * @param mixed $data The initial data for the form
     * @param array $options Options for the form
     *
     * @return Form
     */
    public function createForm($type, $data = null, array $options = array()) {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

}