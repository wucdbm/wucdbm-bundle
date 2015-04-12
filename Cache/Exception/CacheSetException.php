<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class CacheSetException extends AbstractCacheException {

    public function __construct($key) {
        parent::__construct($key);
        $this->message = 'Could not set data for key '.$key;
    }

}