<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Result;

/**
 * $id stands for object ID
 * $key stands for cache key
 * $value is cache value
 *
 * Class MultiGetResult
 * @package Wucdbm\Bundle\WucdbmBundle\Cache\Result
 */
class MultiGetResult {

    /**
     * Array of key-value pairs
     *
     * $key => $value
     *
     * Key is cache key, value is cache value
     *
     * @var array
     */
    protected $cached = array();

    /**
     * Array of key-value pairs
     *
     * $id => $key
     *
     * Key is object ID, value is cache key
     *
     * @var array
     */
    protected $missed = array();

    /**
     * An array that holds the originally passed keys - $key => $id
     * @var array
     */
    protected $keys = array();

    /**
     * An array that holds $key => $value pairs to be cached
     * @var array
     */
    protected $set = array();

    public function __construct($keys, $defaultValue = null) {
        $this->keys = $keys;
        foreach ($keys as $key => $id) {
            $this->hit($key, null);
        }
    }

    public function hit($key, $value) {
        $this->cached[$key] = $value;
    }

    public function hitMissed($id, $value) {
        $key = $this->missed[$id];
        $this->hit($key, $value);
        $this->set($key, $value);
    }

    public function miss($id, $key) {
        $this->missed[$id] = $key;
    }

    public function set($key, $value) {
        $this->set[$key] = $value;
    }

    public function getCached() {
        return $this->cached;
    }

    public function getMissed() {
        return $this->missed;
    }

    public function getSet() {
        return $this->set;
    }

    public function getKeys() {
        return $this->keys;
    }

    public function getMissedIds() {
            return array_keys($this->missed);
    }

}