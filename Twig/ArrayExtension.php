<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Doctrine\Common\Collections\Collection;

class ArrayExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('array_chunk', [$this, 'array_chunk'])
        ];
    }

    public function array_chunk($array, $size, $preserve_key = false) {
        if (is_array($array)) {
            return array_chunk($array, $size, $preserve_key);
        }
        if ($array instanceof Collection) {
            $ret = [];
            $key = 0;
            while (count($slice = $array->slice($key * $size, $size))) {
                $ret[$key++] = $slice;
            }

            return $ret;
        }
    }

    public function getName() {
        return 'wucdbm_array';
    }

    public function getAlias() {
        return 'wucdbm_array';
    }
}