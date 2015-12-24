<?php

namespace Wucdbm\Bundle\WucdbmBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Wucdbm\Bundle\WucdbmBundle\Cache\CacheAwareTrait;

class AbstractManager implements ContainerAwareInterface {

    use CacheAwareTrait;
    use ContainerAwareTrait;

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

        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
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

    /**
     * Returns a rendered view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public function renderView($view, array $parameters = array()) {
        return $this->container->get('templating')->render($view, $parameters);
    }
}