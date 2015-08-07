<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ControllerActionName extends \Twig_Extension {

    /**
     * @var RequestStack
     */
    protected $stack;

    public function __construct(RequestStack $stack) {
        $this->stack = $stack;
    }

    public function getFilters() {
        return array(
            'isActionAndController' => new \Twig_Filter_Method($this, 'isActionAndController'),
            'isController'          => new \Twig_Filter_Method($this, 'isController'),
            'isAction'              => new \Twig_Filter_Method($this, 'isAction'),
            'isRoute'               => new \Twig_Filter_Method($this, 'isRoute'),
        );
    }


    public function getFunctions() {
        return array(
            'controllerName'        => new \Twig_Function_Method($this, 'controllerName'),
            'actionName'            => new \Twig_Function_Method($this, 'actionName'),
            'isActionAndController' => new \Twig_Function_Method($this, 'isActionAndController'),
            'isController'          => new \Twig_Function_Method($this, 'isController'),
            'isAction'              => new \Twig_Function_Method($this, 'isAction'),
            'isRoute'               => new \Twig_Function_Method($this, 'isRoute'),
        );
    }

    /**
     * Get current controller name
     */
    public function controllerName() {
        $request = $this->stack->getCurrentRequest();
        if ($request instanceof Request) {
            $string = $request->get('_controller');
            $parts = explode('::', $string);
            $controller = $parts[0];
            $pattern = "#Controller\\\([a-zA-Z\\\]*)Controller#";
            $matches = array();
            preg_match($pattern, $controller, $matches);
            if (isset($matches[1])) {
                return strtolower(str_replace('\\', '_', $matches[1]));
            }

            return '';
        }

        return '';
    }

    /**
     * Get current action name
     */
    public function actionName() {
        $request = $this->stack->getCurrentRequest();
        if ($request instanceof Request) {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $request->get('_controller'), $matches);
            if (isset($matches[1])) {
                return strtolower($matches[1]);
            }

            return '';
        }

        return '';
    }

    /**
     * Get current route name
     */
    public function routeName() {
        $request = $this->stack->getCurrentRequest();
        if ($request instanceof Request) {
            return $request->get('_route');
        }

        return '';
    }

    public function isRoute($route, $print = '') {
        if (is_array($route)) {
            foreach ($route as $rt) {
                if ($this->_isRoute($rt)) {
                    return $print;
                }
            }
        } else if (is_string($route)) {
            if ($this->_isRoute($route)) {
                return $print;
            }
        }

        return '';
    }

    protected function _isRoute($route) {
        return $this->routeName() == $route;
    }

    public function isController($controller, $print = '') {
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