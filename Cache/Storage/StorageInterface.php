<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheSetException;

interface StorageInterface {

    /**
     * Retrieve an item from the cache by key.
     *
     * @param $key
     * @param bool $strict
     * @param null $default
     *
     * @return mixed
     *
     * @throws CacheMissException
     */
    public function get($key, $strict = true, $default = null);

    /**
     * Retrieve an item from the cache by key.
     *
     * First param contains identifiers, which are imploded with the rest of the parameters to form keys
     *
     * @return mixed
     */
    public function getMulti();

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $seconds
     * @param  bool $strict
     *
     * @throws CacheSetException
     *
     * @return void
     */
    public function set($key, $value, $seconds, $strict = true);

    /**
     * Store items in the cache for a given number of seconds.
     *
     * @param  array $data
     * @param  int $seconds
     * @param  bool $strict
     * @return void
     */
    public function setMulti($data, $seconds, $strict = true);

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function forever($key, $value);

    /**
     * Store items in the cache indefinitely.
     *
     * @param  array $data
     * @param  bool $strict
     * @return void
     */
    public function foreverMulti($data, $strict = true);

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function increment($key, $value = 1);

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1);

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function remove($key);

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush();

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix();

}