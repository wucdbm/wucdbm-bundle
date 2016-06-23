<?php

namespace Wucdbm\Bundle\WucdbmBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CacheCompiler implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (!$container->has('wucdbm.cache.collection')) {
            return;
        }

        $definition = $container->findDefinition('wucdbm.cache.collection');

        $taggedServices = $container->findTaggedServiceIds('wucdbm.cache');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addStorage',
                [$id, new Reference($id)]
            );
        }
    }

}