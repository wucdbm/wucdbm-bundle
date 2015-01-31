<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Storage;

abstract class AbstractStorage implements StorageInterface {

    public function generateKey() {
        $arguments = func_get_args();
        return implode('.', $arguments);
    }

    /**
     * @param $arguments
     * @return array
     * @throws \Exception
     */
    protected function generateKeys($arguments) {
        $ids = array_shift($arguments);
        if (!is_array($ids)) {
            throw new \Exception('First value must be an array.');
        }
        $keys = array();
        foreach ($ids as $id) {
            $arguments['id'] = $id;
            $key = implode('.', $arguments);
            $keys[$key] = $id;
        }
        return $keys;
    }

}