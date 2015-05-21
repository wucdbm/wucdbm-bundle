<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache;

use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\AbstractStorage;

trait CacheAwareTrait {

    /**
     * @var AbstractStorage
     */
    protected $localCache;

    /**
     * @var AbstractStorage
     */
    protected $cache;

    /**
     * @return AbstractStorage
     */
    public function getLocalCache() {
        return $this->localCache;
    }

    /**
     * @param AbstractStorage $localCache
     */
    public function setLocalCache(AbstractStorage $localCache) {
        $this->localCache = $localCache;
    }

    /**
     * @return AbstractStorage
     */
    public function getCache() {
        return $this->cache;
    }

    /**
     * @param AbstractStorage $cache
     */
    public function setCache(AbstractStorage $cache) {
        $this->cache = $cache;
    }

}
