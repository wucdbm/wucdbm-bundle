<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

class NullStorage implements StorageInterface {

    /**
     * The array of stored values.
     *
     * @var array
     */
    protected $storage = array();

    /**
     * Retrieve an item from the cache by key.
     * @param $key
     * @param bool $strict
     * @param null $default
     * @return void
     */
    public function get($key, $strict = true, $default = null) {
        //
    }

    public function getMulti($keys) {
        //
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $seconds
     * @param  bool $strict
     * @return void
     */
    public function set($key, $value, $seconds, $strict = true) {
        //
    }

    public function setMulti($data, $seconds, $strict = true) {
        //
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function forever($key, $value) {
        //
    }

    public function foreverMulti($data, $strict = true) {
        //
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int
     */
    public function increment($key, $value = 1) {
        //
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int
     */
    public function decrement($key, $value = 1) {
        //
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return void
     */
    public function remove($key) {
        //
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush() {
        //
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix() {
        return '';
    }

}