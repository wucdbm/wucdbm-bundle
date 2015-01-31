<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\NoDataException;

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
     * @throws NoDataException
     */
    public function get($key, $strict = true, $default = null);

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $seconds
     * @return void
     */
    public function set($key, $value, $seconds);

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int|bool
     */
    public function increment($key, $value = 1);

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int|bool
     */
    public function decrement($key, $value = 1);

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function forever($key, $value);

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
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