<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Result\MultiGetResult;

class ArrayStorage extends AbstractStorage {

    /**
     * The array of stored values.
     *
     * @var array
     */
    protected $storage = array();

    /**
     * Retrieve an item from the cache by key.
     *
     * @param $key
     * @param bool $strict
     * @param null $default
     *
     * @return null
     *
     * @throws CacheMissException
     */
    public function get($key, $strict = true, $default = null) {
        if (array_key_exists($key, $this->storage)) {
            return $this->storage[$key];
        }
        if ($strict) {
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
     * @return void
     */
    public function set($key, $value, $seconds, $strict = true) {
        $this->storage[$key] = $value;
    }

    public function setMulti($data, $seconds, $strict = true) {
        foreach ($data as $key => $value) {
            $this->set($key, $value, $seconds);
        }
    }

    public function foreverMulti($data, $strict = true) {
        $this->setMulti($data, 0, $strict);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int
     */
    public function increment($key, $value = 1) {
        $this->storage[$key] = $this->storage[$key] + $value;

        return $this->storage[$key];
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int
     */
    public function decrement($key, $value = 1) {
        return $this->increment($key, $value * -1);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param $key
     * @param $value
     * @param bool $strict
     * @return void
     */
    public function forever($key, $value, $strict = true) {
        $this->set($key, $value, 0, $strict);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function remove($key) {
        unset($this->storage[$key]);

        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush() {
        $this->storage = array();
    }

}