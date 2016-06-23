<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache;

use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\AbstractStorage;

class CacheCollection {

    protected $storages = [];

    /**
     * @param $serviceId
     * @param AbstractStorage $storage
     */
    public function addStorage($serviceId, AbstractStorage $storage) {
        $this->storages[$serviceId] = $storage;
    }

    /**
     * @return AbstractStorage[]
     */
    public function getStorages() {
        return $this->storages;
    }

}