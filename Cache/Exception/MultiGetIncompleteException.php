<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class MultiGetIncompleteException extends \Exception {

    protected $keys;

    public function __construct($keys) {
        $this->keys    = $keys;
        $this->message = 'Could not set data for some keys';
    }

    /**
     * @return mixed
     */
    public function getKeys() {
        return $this->keys;
    }

    /**
     * @param mixed $keys
     */
    public function setKeys($keys) {
        $this->keys = $keys;
    }

}