<?php

namespace Printdeal\PandosearchBundle\DependencyInjection\Compiler;

use Printdeal\PandosearchBundle\Service\QueryBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueryBuildersPass implements CompilerPassInterface
{
    const BUILDER_TAG = 'printdeal.pandosearch.builder';

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(QueryBuilder::class)) {
            return;
        }

        $queryBuilderService = $container->findDefinition(QueryBuilder::class);

        $builders = array_keys($container->findTaggedServiceIds(self::BUILDER_TAG));
        foreach ($builders as $clientId) {
            $queryBuilderService->addMethodCall(
                'addBuilder',
                [new Reference($clientId)]
            );
        }
    }
}
