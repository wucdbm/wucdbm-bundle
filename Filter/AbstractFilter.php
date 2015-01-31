<?php

namespace Wucdbm\Bundle\WucdbmBundle\Filter;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AbstractFilter {

    private $page = 1;

    private $limit = 20;

    /**
     * @var Pagination
     */
    private $pagination = null;

    private $paginationParams = array();

    private $reflection = null;

    private $_options = array(
        self::OPTION_HYDRATION => self::OPTION_HYDRATION_OBJECT
    );

    const OPTION_HYDRATION = 'hydration';
    /* Hydration mode constants */
    /**
     * Hydrates an object graph. This is the default behavior.
     */
    const OPTION_HYDRATION_OBJECT = 1;
    /**
     * Hydrates an array graph.
     */
    const OPTION_HYDRATION_ARRAY = 2;
    /**
     * Hydrates a flat, rectangular result set with scalar values.
     */
    const OPTION_HYDRATION_SCALAR = 3;
    /**
     * Hydrates a single scalar value.
     */
    const OPTION_HYDRATION_SINGLE_SCALAR = 4;
    /**
     * Very simple object hydrator (optimized for performance).
     */
    const OPTION_HYDRATION_SIMPLEOBJECT = 5;

    public function getHydrationMode() {
        return $this->getOption(self::OPTION_HYDRATION);
    }

    public function setHydrationObject() {
        return $this->setOption(self::OPTION_HYDRATION, self::OPTION_HYDRATION_OBJECT);
    }

    public function setHydrationArray() {
        return $this->setOption(self::OPTION_HYDRATION, self::OPTION_HYDRATION_ARRAY);
    }

    public function getOption($name) {
        return $this->_options[$name];
    }

    public function setOption($name, $value) {
        $this->_options[$name] = $value;
        return $this;
    }

    public function isOption($name, $value) {
        return $this->_options[$name] == $value;
    }

    /**
     * @return null|\ReflectionClass
     */
    public function getReflection() {
        if (null === $this->reflection) {
            $this->reflection = new \ReflectionClass($this);
        }
        return $this->reflection;
    }

    /**
     * @deprecated
     * @param Request $request
     */
    public function loadFromRequest(Request $request) {
        $vars = $this->getProtectedVars();
        foreach ($vars as $var) {
            if ($request->query->get($var) !== null) {
                $this->$var = $request->query->get($var);
            }
        }
        $page = $request->get('page');
        $this->setPage(is_numeric($page) ? $page : 1);
        $this->pagination->setPage($this->getPage());
        $this->pagination->setLimit($this->getLimit());
    }

    public function load(Request $request, Form $form) {
        $defaultLimit = $this->getLimit();
        $get = $request->query->all();
        if (!isset($get['limit']) || !is_numeric($get['limit'])) {
            $request->query->set('limit', $defaultLimit);
        }
        $form->handleRequest($request);
        $this->paginationParams = array_merge_recursive($get, $request->get('_route_params'));
        $this->pagination->setRoute($request->get('_route'));
        if (isset($get['page']) && is_numeric($get['page'])) {
            $this->setPage($get['page']);
            $this->getPagination()->setPage($this->getPage());
        }
        $this->pagination->setLimit($this->getLimit());
        return $this;
    }

    public function getProtectedVars() {
        $reflection = $this->getReflection();
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
        $ret = array();
        foreach ($vars as $var) {
            $ret[] = $var->name;
        }
        return $ret;
    }

    public function getProtectedValues() {
        $vars   = $this->getProtectedVars();
        $params = array();
        foreach ($vars as $var) {
            if (is_object($this->$var)) {
                $params[$var] = $this->$var->getId();
            } else {
                $params[$var] = $this->$var;
            }
        }
        return $params;
    }

    public function has($name) {
        if ($this->$name === 0) {
            return true;
        }
        return isset($this->$name) && $this->$name != '' && $this->$name != null;
    }

    public function __set($name, $value) {
        $reflection = $this->getReflection();
        if (!$reflection->hasProperty($name)) {
            throw new \Exception('Filter ' . get_class($this) . ' does not have property ['.$name.']. Maybe you forgot to implement it first?');
        }
        $this->$name = $value;
    }

    public function __get($name) {
        $reflection = $this->getReflection();
        if (!$reflection->hasProperty($name)) {
            throw new \Exception('Filter ' . get_class($this) . ' does not have property ['.$name.']. Maybe you forgot to implement it first?');
        }
        return $this->$name;
    }

    public function __call($name, $arguments) {
        if (is_callable('get_'.$name)) {
            return call_user_func_array(array($this, 'get_'.$name), $arguments);
        } else if (isset($this->$name)) {
            return $this->$name;
        }
    }

    public function __construct() {
        $pagination = new Pagination($this);
        $this->setPagination($pagination);
    }

    /**
     * @return array
     */
    public function getOptions() {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options) {
        $this->_options = $options;
    }

    /**
     * @return int
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * @return Pagination
     */
    public function getPagination() {
        return $this->pagination;
    }

    /**
     * @param Pagination $pagination
     */
    public function setPagination($pagination) {
        $this->pagination = $pagination;
    }

    /**
     * @return array
     */
    public function getPaginationParams() {
        return $this->paginationParams;
    }

    /**
     * @param array $paginationParams
     */
    public function setPaginationParams($paginationParams) {
        $this->paginationParams = $paginationParams;
    }

    /**
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

}