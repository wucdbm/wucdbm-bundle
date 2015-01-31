<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheGetFailedException;

class MemcachedStorage implements StorageInterface {

    /**
     * The Memcached instance.
     *
     * @var \Memcached
     */
    protected $memcached;
    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Create a new Memcached store.
     *
     * @param  \Memcached $memcached
     * @param  string $prefix
     */
    public function __construct(\Memcached $memcached, $prefix = '') {
        $this->memcached = $memcached;
        $this->prefix    = strlen($prefix) > 0 ? $prefix . ':' : '';
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
     * @throws CacheGetFailedException
     */
    public function get($key, $strict = true, $default = null) {
        $value = $this->memcached->get($this->prefix . $key);
        $resultCode = $this->memcached->getResultCode();
        if ($resultCode == \Memcached::RES_SUCCESS) {
            return $value;
        }
        if (\Memcached::RES_NOTFOUND == $resultCode && $strict) {
            throw new CacheGetFailedException($key);
        }
        return $default;
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $seconds
     * @param  bool $strict
     * @return bool
     */
    public function set($key, $value, $seconds, $strict = true) {
        $set = $this->memcached->set($this->prefix . $key, $value, $seconds);
        if (!$set && $strict) {

        }
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