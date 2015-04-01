<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class PhpExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'get_class' => new \Twig_Filter_Method($this, 'get_class'),
            'fqcn'      => new \Twig_Filter_Method($this, 'fqcn')
        );
    }

    public function getFunctions() {
        return array(
            'get_class' => new \Twig_Function_Method($this, 'get_class'),
            'fqcn'      => new \Twig_Function_Method($this, 'fqcn')
        );
    }

    public function get_class($object) {
        $fqcn = $this->fqcn($object);
        if ($fqcn) {
            $parts = explode('\\', $fqcn);
            $short = array_pop($parts);
            return $short;
        }
        return '';
    }

    public function fqcn($object) {
        if (is_object($object)) {
            return get_class($object);
        }
        return '';
    }

    public function getName() {
        return 'wucdbm_php';
    }

    public function getAlias() {
        return 'wucdbm_php';
    }
}