<?php

namespace Wucdbm\Bundle\WucdbmBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WucdbmWucdbmExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $config = array();
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services/core.xml');
        $loader->load('services/expression.xml');
        $loader->load('services/form/filter_types.xml');
        $loader->load('services/form/types.xml');
        $loader->load('services/twig.xml');

    }

    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config/';
    }

    public function getNamespace() {
        return 'http://www.example.com/symfony/schema/';
    }

}