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
                ->append($this->guzzleSettings())
                ->append($this->defaultQueryOptions())
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function defaultQueryOptions()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('query_settings');

        $node->children()
                ->booleanNode('track')->end()
                ->booleanNode('full')->end()
                ->booleanNode('nocorrect')->end()
                ->booleanNode('notiming')->end()
            ->end();

        return $node;
    }

    private function guzzleSettings()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('guzzle_client');

        $node->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('timeout')
                        ->defaultValue(PrintdealPandosearchExtension::DEFAULT_GUZZLE_TIMEOUT)
                    ->end()
                    ->integerNode('connect_timeout')
                        ->defaultValue(PrintdealPandosearchExtension::DEFAULT_GUZZLE_CONNECT_TIMEOUT)
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
