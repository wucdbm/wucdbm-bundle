<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class CacheSetFailedException extends \Exception {

    public function __construct($key) {
        $this->message = 'Could not set data for key '.$key;
    }

}