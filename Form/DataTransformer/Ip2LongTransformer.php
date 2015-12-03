<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class Ip2LongTransformer implements DataTransformerInterface {

    public function transform($long) {
//        var_dump($long);
        if (null === $long) {
            return null;
        }

        $ip = long2ip($long);
//        var_dump($long);
//        var_dump($ip);
//        exit('transform');


        return $ip ? $ip : null;
    }

    public function reverseTransform($ip) {
        if (!$ip) {
            return null;
        }

        $long = ip2long($ip);
//        var_dump($long);
//        var_dump($ip);
//        exit('reverseTransform');

        return $long ? $long : null;
    }
}