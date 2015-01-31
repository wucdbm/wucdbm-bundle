<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Result;

class MultiGetResult {

    /**
     * Array of key-value pairs
     *
     * Key is cache key, value is cache value
     *
     * @var array
     */
    protected $cached = array();

    /**
     * Array of key-value pairs
     *
     * Key is object ID, value is cache key
     *
     * @var array
     */
    protected $missed = array();

    public function __construct(array $result, array $missed) {
        $this->cached = $result;
        $this->missed = $missed;
    }

    public function setCached($key, $value) {
        $this->cached[$key] = $value;
    }

    public function getCached() {
        return $this->cached;
    }

    public function getMissed() {
        return $this->missed;
    }

    public function getMissedIds() {
        return array_keys($this->missed);
    }

}