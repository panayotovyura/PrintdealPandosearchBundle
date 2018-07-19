<?php

namespace Printdeal\PandosearchBundle;

use Printdeal\PandosearchBundle\DependencyInjection\Compiler\HttpClientsPass;
use Printdeal\PandosearchBundle\DependencyInjection\Compiler\QueryBuildersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PrintdealPandosearchBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HttpClientsPass());
        $container->addCompilerPass(new QueryBuildersPass());
    }
}
