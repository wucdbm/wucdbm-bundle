<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheSetException;

class WrappedStorage extends AbstractStorage {

    /**
     * The Memcached instance.
     *
     * @var AbstractStorage
     */
    protected $storage;

    /**
     * Create a new Memcached store.
     *
     * @param  AbstractStorage $storage
     * @param  string $prefix
     */
    public function __construct(AbstractStorage $storage, $prefix = '') {
        $this->storage = $storage;
        parent::__construct($storage->getPrefix() . $prefix);
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
        return $this->storage->get($key, $strict, $default);
    }

    public function getMulti($keys) {
        return $this->storage->getMulti($keys);
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
        $this->storage->set($key, $value, $seconds, $strict);
    }

    /**
     * @param array $data
     * @param int $seconds
     * @param bool $strict
     * @throws CacheSetException
     */
    public function setMulti($data, $seconds, $strict = true) {
        $this->storage->setMulti($data, $seconds, $strict);
    }

    /**
     * @param array $data
     * @param bool $strict
     * @throws CacheSetException
     */
    public function foreverMulti($data, $strict = true) {
        $this->storage->setMulti($data, 0, $strict);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function increment($key, $value = 1) {
        return $this->storage->increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1) {
        return $this->storage->decrement($key, $value);
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
        return $this->storage->remove($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush() {
        $this->storage->flush();
    }

    /**
     * Get the underlying Memcached connection.
     *
     * @return AbstractStorage
     */
    public function getStorage() {
        return $this->storage;
    }
}