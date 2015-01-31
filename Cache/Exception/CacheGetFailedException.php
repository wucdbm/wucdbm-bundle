<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Exception;

class CacheGetFailedException extends \Exception {

    public function __construct($key) {
        $this->message = 'No data was found for key '.$key;
    }

}