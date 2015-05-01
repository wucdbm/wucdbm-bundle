<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheSetException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Result\MultiGetResult;

class MemcacheStorage extends AbstractStorage {

    /**
     * The Memcache instance.
     *
     * @var \Memcache
     */
    protected $memcache;
    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Create a new Memcache store.
     *
     * @param  \Memcache $memcache
     * @param  string $prefix
     */
    public function __construct(\Memcache $memcache, $prefix = '') {
        $this->memcache = $memcache;
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
        $value = $this->memcache->get($key);
        if (false === $value && $strict) {
            throw new CacheMissException($key);
        }
        return $default;
    }

    public function getMulti($keys) {
        $result = new MultiGetResult($keys);
        foreach ($keys as $key => $id) {
            try {
                $result->hit($key, $this->get($key));
            } catch (CacheMissException $ex) {
                $result->miss($id, $key);
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
        $set = $this->memcache->set($key, $value, $seconds);
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
        foreach ($data as $key => $value) {
            $this->memcache->set($key, $value, 0, $seconds);
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
        return $this->memcache->increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1) {
        return $this->memcache->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  bool $strict
     * @return bool
     */
    public function forever($key, $value, $strict = true) {
        return $this->set($key, $value, 0, $strict);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function remove($key) {
        return $this->memcache->delete($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush() {
        $this->memcache->flush();
    }

    /**
     * Get the underlying Memcache connection.
     *
     * @return \Memcache
     */
    public function getMemcache() {
        return $this->memcache;
    }
}