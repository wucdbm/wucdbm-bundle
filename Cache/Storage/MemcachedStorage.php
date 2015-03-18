<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheSetException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Result\MultiGetResult;

class MemcachedStorage extends AbstractStorage {

    /**
     * The Memcached instance.
     *
     * @var \Memcached
     */
    protected $memcached;

    /**
     * Create a new Memcached store.
     *
     * @param  \Memcached $memcached
     * @param  string $prefix
     */
    public function __construct(\Memcached $memcached, $prefix = '') {
        $this->memcached = $memcached;
        parent::__construct($prefix);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param $key
     * @param bool $strict
     * @param null $default
     *
     * @return mixed|null
     *
     * @throws CacheMissException
     */
    public function get($key, $strict = true, $default = null) {
        $value      = $this->memcached->get($this->prefix . $key);
        $resultCode = $this->memcached->getResultCode();
        if ($resultCode == \Memcached::RES_SUCCESS) {
            return $value;
        }
        if (\Memcached::RES_NOTFOUND == $resultCode && $strict) {
            throw new CacheMissException($key);
        }
        return $default;
    }

    public function getMulti($keys) {
        $result = new MultiGetResult($keys);
        $null   = null;
        $cached = $this->memcached->getMulti(array_keys($keys), $null, \Memcached::GET_PRESERVE_ORDER);
        foreach ($cached as $key => $value) {
            if (null === $value) {
                $id = $keys[$key];
                $result->miss($id, $key);
            } else {
                $result->hit($key, $value);
            }
        }
        return $result;
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $seconds
     * @param  bool $strict
     *
     * @throws CacheSetException
     *
     * @return bool
     */
    public function set($key, $value, $seconds, $strict = true) {
        $set = $this->memcached->set($this->prefix . $key, $value, $seconds);
        if (!$set && $strict) {
            throw new CacheSetException($key);
        }
    }

    /**
     * @param array $data
     * @param int $seconds
     * @param bool $strict
     * @throws CacheSetException
     */
    public function setMulti($data, $seconds, $strict = true) {
        $set = $this->memcached->setMulti($data, $seconds);
        if (!$set && $strict) {
            throw new CacheSetException('multi');
        }
    }

    /**
     * @param array $data
     * @param bool $strict
     * @throws CacheSetException
     */
    public function foreverMulti($data, $strict = true) {
        $this->setMulti($data, 0, $strict);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function increment($key, $value = 1) {
        return $this->memcached->increment($this->prefix . $key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1) {
        return $this->memcached->decrement($this->prefix . $key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function forever($key, $value) {
        return $this->set($key, $value, 0);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function remove($key) {
        return $this->memcached->delete($this->prefix . $key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush() {
        $this->memcached->flush();
    }

    /**
     * Get the underlying Memcached connection.
     *
     * @return \Memcached
     */
    public function getMemcached() {
        return $this->memcached;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }
}