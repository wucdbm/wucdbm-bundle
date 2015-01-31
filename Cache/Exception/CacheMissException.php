<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class CacheMissException extends AbstractCacheException {

    public function __construct($key) {
        parent::__construct($key);
        $this->message = 'No data was found for key '.$key;
    }

}