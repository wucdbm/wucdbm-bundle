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
        throw new \Exception('Parameter "array" is not an array or any of the supported collection types in "array_chunk".');
    }

    public function getName() {
        return 'wucdbm_array';
    }

}