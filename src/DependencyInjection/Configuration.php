<?php

namespace Printdeal\PandosearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('printdeal_pandosearch');

        $rootNode
            ->children()
                ->scalarNode('company_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('query_settings')
                    ->children()
                        ->booleanNode('track')->end()
                        ->booleanNode('full')->end()
                        ->booleanNode('nocorrect')->end()
                        ->booleanNode('notiming')->end()
                    ->end()
                ->end()
                ->arrayNode('guzzle_client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('timeout')
                            ->defaultValue(PrintdealPandosearchExtension::DEFAULT_GUZZLE_TIMEOUT)
                        ->end()
                        ->integerNode('connect_timeout')
                            ->defaultValue(PrintdealPandosearchExtension::DEFAULT_GUZZLE_CONNECT_TIMEOUT)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
