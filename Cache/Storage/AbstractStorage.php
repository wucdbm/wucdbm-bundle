<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

abstract class AbstractStorage implements StorageInterface {

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix = '') {
        $this->prefix = $this->cleanupPrefix($prefix);
    }

    /**
     * @param $args
     * @return string
     */
    public function generateKey($args) {
        return $this->makeKey(func_get_args());
    }

    /**
     * Returns an array of $cacheKey => $objectId
     *
     * @param $objectIds
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function generateKeys($objectIds, $args) {
        $arguments = func_get_args();
        $objectIds = array_shift($arguments);
        if (!is_array($objectIds)) {
            throw new \Exception('First value must be an array.');
        }
        $keys = array();
        foreach ($objectIds as $objectId) {
            $arguments['id'] = $objectId;
            $key             = $this->makeKey($arguments);
            $keys[$key]      = $objectId;
        }
        return $keys;
    }

    protected function makeKey($arguments) {
        return $this->getPrefix() . str_replace(' ', '_', implode('.', $arguments));
    }

    protected function cleanupPrefix($prefix) {
        return strlen($prefix) > 0 ? str_replace(' ', '_', $prefix) . ':' : '';
    }

    /**
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }

}