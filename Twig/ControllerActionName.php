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
            'isContoller'           => new \Twig_Filter_Method($this, 'isContoller'),
            'isAction'              => new \Twig_Filter_Method($this, 'isAction'),
            'isActionAndController' => new \Twig_Filter_Method($this, 'isActionAndController')
        );
    }


    public function getFunctions() {
        return array(
            'controllerName'        => new \Twig_Function_Method($this, 'controllerName'),
            'actionName'            => new \Twig_Function_Method($this, 'actionName'),
            'isContoller'           => new \Twig_Function_Method($this, 'isContoller'),
            'isAction'              => new \Twig_Function_Method($this, 'isAction'),
            'isActionAndController' => new \Twig_Function_Method($this, 'isActionAndController'),
        );
    }

    /**
     * Get current controller name
     */
    public function controllerName() {
        if (null !== $this->container->get('request')) {
            $string     = $this->container->get('request')->get('_controller');
            $parts      = explode('::', $string);
            $controller = $parts[0];
            $pattern    = "#Controller\\\([a-zA-Z\\\]*)Controller#";
            $matches    = array();
            preg_match($pattern, $controller, $matches);
            if (isset($matches[1])) {
                return strtolower(str_replace('\\', '_', $matches[1]));
            }
            return '';
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
            if (isset($matches[1])) {
                return strtolower($matches[1]);
            }
            return '';
        }
    }

    public function isContoller($controller, $print = '') {
        if (is_array($controller)) {
            foreach ($controller as $ctrl) {
                if ($this->_isController($ctrl)) {
                    return $print;
                }
            }
        } else if (is_string($controller)) {
            if ($this->_isController($controller)) {
                return $print;
            }
        }
        return '';
    }

    protected function _isController($controller) {
        return $this->controllerName() == $controller;
    }

    public function isAction($action, $print = '') {
        if (is_array($action)) {
            foreach ($action as $act) {
                if ($this->_isAction($act)) {
                    return $print;
                }
            }
        } else if (is_string($action)) {
            if ($this->_isAction($action)) {
                return $print;
            }
        }
        return '';
    }

    protected function _isAction($action) {
        return $this->actionName() == $action;
    }

    public function isActionAndController($action, $controller, $print = '') {
        if ($this->_isAction($action) && $this->_isController($controller)) {
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