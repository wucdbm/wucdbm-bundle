<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class PhpExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('get_class', [$this, 'get_class']),
            new \Twig_SimpleFilter('fqcn', [$this, 'fqcn']),
            new \Twig_SimpleFilter('unserialize', 'unserialize'),
            new \Twig_SimpleFilter('file_get_contents', 'file_get_contents'),
            new \Twig_SimpleFilter('intval', 'intval'),
            new \Twig_SimpleFilter('floatval', 'floatval')
        ];
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('get_class', [$this, 'get_class']),
            new \Twig_SimpleFunction('fqcn', [$this, 'fqcn']),
            new \Twig_SimpleFunction('unserialize', 'unserialize'),
            new \Twig_SimpleFunction('file_get_contents', 'file_get_contents'),
            new \Twig_SimpleFunction('intval', 'intval'),
            new \Twig_SimpleFunction('floatval', 'floatval')
        ];
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

    public function getName() {
        return 'wucdbm_php';
    }

    public function getAlias() {
        return 'wucdbm_php';
    }
}