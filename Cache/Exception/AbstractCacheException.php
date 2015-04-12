<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class AbstractCacheException extends \Exception {

    protected $key;

    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

}