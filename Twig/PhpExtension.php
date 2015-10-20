<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class PhpExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'get_class'   => new \Twig_Filter_Method($this, 'get_class'),
            'fqcn'        => new \Twig_Filter_Method($this, 'fqcn'),
            'unserialize' => new \Twig_Filter_Method($this, 'unserialize'),
            'read'        => new \Twig_Filter_Function('file_get_contents'),
        );
    }

    public function getFunctions() {
        return array(
            'get_class'   => new \Twig_Function_Method($this, 'get_class'),
            'fqcn'        => new \Twig_Function_Method($this, 'fqcn'),
            'unserialize' => new \Twig_Function_Method($this, 'unserialize'),
            'read'        => new \Twig_Function_Function('file_get_contents'),
        );
    }

    public function get_class($object, $default = '') {
        $fqcn = $this->fqcn($object);
        if ($fqcn) {
            $parts = explode('\\', $fqcn);
            $short = array_pop($parts);

            return $short;
        }

        return $default;
    }

    public function fqcn($object, $default = '') {
        if (is_object($object)) {
            return get_class($object);
        }

        return $default;
    }

    public function unserialize($string) {
        return unserialize($string);
    }

    public function getName() {
        return 'wucdbm_php';
    }

    public function getAlias() {
        return 'wucdbm_php';
    }
}