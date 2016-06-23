<?php

namespace Wucdbm\Bundle\WucdbmBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wucdbm\Bundle\WucdbmBundle\DependencyInjection\Compiler\CacheCompiler;

class WucdbmWucdbmBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new CacheCompiler());
    }

}
