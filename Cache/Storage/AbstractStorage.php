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
        $this->prefix = strlen($prefix) > 0 ? $prefix . ':' : '';
    }

    public function generateKey() {
        $arguments = func_get_args();
        return implode('.', $arguments);
    }

    /**
     * Returns an array of $cacheKey => $objectId
     *
     * @return array
     * @throws \Exception
     */
    public function generateKeys() {
        $arguments = func_get_args();
        $ids       = array_shift($arguments);
        if (!is_array($ids)) {
            throw new \Exception('First value must be an array.');
        }
        $keys = array();
        foreach ($ids as $id) {
            $arguments['id'] = $id;
            $key             = implode('.', $arguments);
            $keys[$key]      = $id;
        }
        return $keys;
    }

}