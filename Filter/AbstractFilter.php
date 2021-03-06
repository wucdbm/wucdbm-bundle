<?php

namespace Wucdbm\Bundle\WucdbmBundle\Filter;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\WucdbmBundle\DataTransformer\ConditionalDataTransformer;
use Wucdbm\Bundle\WucdbmBundle\DataTransformer\DataTransformerAwareTrait;

/**
 * Class AbstractFilter
 * @package Wucdbm\Bundle\WucdbmBundle\Filter
 */
class AbstractFilter {

    use DataTransformerAwareTrait;

    private $page = 1;

    private $limit = 20;

    /**
     * Page Request var name
     * @var string
     */
    private $pageVar = 'page';

    /**
     * Limit Request var name
     * @var string
     */
    private $limitVar = 'limit';

    /**
     * String NS for loading vars from Symfony Request object
     * @var string
     */
    private $namespace = '';

    /**
     * Request type - GET or POST
     * @var string
     */
    private $type = 'GET';

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
     * @param Request $request
     * @param null $type
     * @param string $namespace
     */
    protected function _load(Request $request, $type = null, $namespace = '') {
        $bag      = $this->getBagByType($request, $type);
        $pageVar  = $this->getVarPathForNamespace($namespace, $this->getPageVar());
        $limitVar = $this->getVarPathForNamespace($namespace, $this->getLimitVar());
        $page     = $bag->get($pageVar, 1, true);
        $limit    = $bag->get($limitVar, $this->getLimit(), true);
        if (0 == $limit) {
            $limit = null;
        }
        $this->setPage($page);
        $pagination = $this->getPagination();
        $pagination->setPage($page);
        $pagination->setLimit($limit);
        $pagination->setParams(array_merge_recursive($bag->all(), $request->get('_route_params')));
        $pagination->setRoute($request->get('_route'));
    }

    /**
     * @param Request $request
     * @param string $namespace
     * @param null $type
     * @return $this
     */
    public function loadFromRequest(Request $request, $namespace = '', $type = null) {
        if (null === $type) {
            $type = $this->getType();
        }

        $this->_load($request, $type, $namespace);
        $bag = $this->getBagByType($request, $this->getType());

        $fields = $this->getProtectedVars();
        foreach ($fields as $field) {
            $varPath = $this->getVarPathForNamespace($namespace, $field);
            if ($val = $bag->get($varPath, null, true)) {
                $this->$field = $val;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function extractPaginationParams($route) {
        $vars = [];
        $fields = $this->getProtectedVars();
        foreach ($fields as $field) {
            if ($this->$field) {
                $vars[$field] = $this->$field;
            }
        }
        $pagination = $this->getPagination();
        $pagination->setPage($this->getPage());
        $pagination->setLimit($this->getLimit());
        $pagination->setParams($vars);
        $pagination->setRoute($route);

        return $this;
    }

    public function transform() {
        $fields = $this->getProtectedVars();
        foreach ($fields as $field) {
            $this->$field = $this->getTransformedValue($field, $this->$field);
        }
        return $this;
    }

    public function reverseTransform() {
        $fields = $this->getProtectedVars();
        foreach ($fields as $field) {
            $this->$field = $this->getReverseTransformedValue($field, $this->$field);
        }
        return $this;
    }

    public function getTransformedValue($field, $value = null) {
        if (null === $value) {
            $value = $this->$field;
        }
        $transformers = $this->getDataTransformers($field);
        foreach ($transformers as $transformer) {
            if ($transformer instanceof ConditionalDataTransformer) {
                if ($transformer->isEligible($value)) {
                    return $transformer->transform($value);
                }
                continue;
            }
            if ($transformer instanceof DataTransformerInterface) {
                return $transformer->transform($value);
            }
        }
        return $value;
    }

    public function getReverseTransformedValue($field, $value = null) {
        if (null === $value) {
            $value = $this->$field;
        }
        $transformers = $this->getDataTransformers($field);
        foreach ($transformers as $transformer) {
            if ($transformer instanceof ConditionalDataTransformer) {
                if ($transformer->isReverseEligible($value)) {
                    return $transformer->reverseTransform($value);
                }
                continue;
            }
            if ($transformer instanceof DataTransformerInterface) {
                return $transformer->reverseTransform($value);
            }
        }
        return $value;
    }

    /**
     * @param Request $request
     * @param Form $form
     * @return $this
     */
    public function load(Request $request, Form $form) {
        $this->_load($request, $form->getConfig()->getMethod(), $form->getName());
        $form->handleRequest($request);
        return $this;
    }

    /**
     * @param $namespace
     * @param $var
     * @return string
     */
    public function getVarPathForNamespace($namespace, $var) {
        if ($namespace) {
            return $namespace . '[' . $var . ']';
        }
        return $var;
    }

    /**
     * @param Request $request
     * @param $type
     * @return ParameterBag
     */
    public function getBagByType(Request $request, $type = null) {
        if ('POST' == $type) {
            return $request->request;
        }
        if ('GET' == $type) {
            return $request->query;
        }
        return $request->query;
    }

    /**
     * @return array
     */
    public function getProtectedVars() {
        $reflection = $this->getReflection();
        $vars       = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
        $ret        = array();
        foreach ($vars as $var) {
            $ret[] = $var->name;
        }
        return $ret;
    }

    /**
     * @return array
     */
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

    /**
     * @return $this
     */
    public function enablePagination() {
        $this->getPagination()->enable();
        return $this;
    }

    public function getMd5() {
        // can't serialize \Closure
        $dataTransformers = $this->getAllDataTransformers();
        $this->removeAllDataTransformers();
        $md5 = md5(serialize($this));
        $this->setDataTransformers($dataTransformers);
        return $md5;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name) {
        if ($this->$name === 0) {
            return true;
        }
        return isset($this->$name) && $this->$name != '' && $this->$name != null;
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value) {
        $reflection = $this->getReflection();
        if (!$reflection->hasProperty($name)) {
            throw new \Exception('Filter ' . get_class($this) . ' does not have property [' . $name . ']. Maybe you forgot to implement it first?');
        }
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name) {
        $reflection = $this->getReflection();
        if (!$reflection->hasProperty($name)) {
            throw new \Exception('Filter ' . get_class($this) . ' does not have property [' . $name . ']. Maybe you forgot to implement it first?');
        }
        return $this->$name;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        if (is_callable('get_' . $name)) {
            return call_user_func_array(array($this, 'get_' . $name), $arguments);
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

    /**
     * @return string
     */
    public function getPageVar() {
        return $this->pageVar;
    }

    /**
     * @param string $pageVar
     */
    public function setPageVar($pageVar) {
        $this->pageVar = $pageVar;
    }

    /**
     * @return string
     */
    public function getLimitVar() {
        return $this->limitVar;
    }

    /**
     * @param string $limitVar
     */
    public function setLimitVar($limitVar) {
        $this->limitVar = $limitVar;
    }

    /**
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

}