<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class ArrayExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('array_chunk', [$this, 'array_chunk'])
        ];
    }

    function array_chunk($input, $size, $preserve_keys = null) {
        if ($input instanceof \Traversable) {
            $arr = array();
            foreach ($input as $val) {
                $arr[] = $val;
            }

            return array_chunk($arr, $size, $preserve_keys);
        }

        return array_chunk($input, $size, $preserve_keys);
    }

    public function getName() {
        return 'wucdbm_array';
    }

}