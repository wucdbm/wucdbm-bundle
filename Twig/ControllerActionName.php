<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ControllerActionName extends \Twig_Extension {

    /**
     * @var ContainerInterface
     */
    protected $container = null;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getFilters() {
        return array(
            'isContoller' => new \Twig_Filter_Method($this, 'isContoller'),
            'isAction'    => new \Twig_Filter_Method($this, 'isAction')
        );
    }


    public function getFunctions() {
        return array(
            'controllerName' => new \Twig_Function_Method($this, 'controllerName'),
            'actionName'     => new \Twig_Function_Method($this, 'actionName'),
            'isContoller'    => new \Twig_Function_Method($this, 'isContoller'),
            'isAction'       => new \Twig_Function_Method($this, 'isAction'),
        );
    }

    /**
     * Get current controller name
     */
    public function controllerName() {
        if (null !== $this->container->get('request')) {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = array();
            preg_match($pattern, $this->container->get('request')->get('_controller'), $matches);
            return strtolower($matches[1]);
        }
    }

    /**
     * Get current action name
     */
    public function actionName() {
        if (null !== $this->container->get('request')) {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $this->container->get('request')->get('_controller'), $matches);
            return $matches[1];
        }
    }

    public function isContoller($controller, $print = '') {
        if ($this->controllerName() == $controller) {
            return $print;
        }
        return '';
    }

    public function isAction($action, $print = '') {
        if ($this->actionName() == $action) {
            return $print;
        }
        return '';
    }

    public function getName() {
        return 'controller_action_name';
    }

    public function getAlias() {
        return 'controller_action_name';
    }
}