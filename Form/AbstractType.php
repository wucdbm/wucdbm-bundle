<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form;

use Symfony\Component\Form\AbstractType as BaseType;

class AbstractType extends BaseType {

    function getBlockPrefix() {
        $fqcn = get_class($this);
        preg_match('/([a-zA-Z\_\d]+\\\+){2}([a-z\\\_\d]+?)(type)?$/i', $fqcn, $matches);
        $formName = $matches[2];

        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/', '/\\\/'), array('\\1_\\2', '\\1_\\2', '_'), $formName));
    }

}