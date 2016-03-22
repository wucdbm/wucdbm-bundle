<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class ArrayExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('array_chunk', [$this, 'array_chunk']),
            new \Twig_SimpleFilter('urlEncodedToArray', [$this, 'urlEncodedToArray'])
        ];
    }

    /**
     * @param $encoded
     * @return \Generator
     */
    public function urlEncodedToArray($encoded) {
        $array = explode('&', $encoded);

        foreach ($array as $v) {
            list($key, $value) = explode('=', $v);
            yield [$key => $value];
        }
    }

    public function array_chunk($input, $size, $preserve_keys = null) {
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