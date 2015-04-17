<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

/** TODO: Wrapped Storage - wraps any abstract storage into itself - useful when you want a different service to only prefix keys with something more than what you already have set as prefix in base implementation */
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
        return $this->makeKey(func_get_args());
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
            $key             = $this->makeKey($arguments);
            $keys[$key]      = $id;
        }
        return $keys;
    }

    protected function makeKey($arguments) {
        return str_replace(' ', '_', implode('.', $arguments));
    }

}