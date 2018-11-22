<?php

namespace Printdeal\PandosearchBundle\DependencyInjection;

use Printdeal\PandosearchBundle\Entity\Search\DefaultResponse as SearchResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\DefaultResponse as SuggestionResponse;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->root('printdeal_pandosearch');

        $rootNode
            ->children()
                ->scalarNode('company_name')
                    ->info('Company domain search provided for.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->append($this->hostSettings())
                ->append($this->guzzleSettings())
                ->append($this->defaultQueryOptions())
                ->append($this->localizationsTree())
                ->append($this->getDeserializationEntity())
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function hostSettings()
    {
        $tree = new TreeBuilder();
        $node = $tree->root('search');

        $node->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('protocol')
                    ->defaultValue('https')
                ->end()
                ->scalarNode('host')
                    ->defaultValue('search.enrise.com')
                    ->info(
                        'Search API host. Can be used to activate search at Enrise acceptance environment.'
                    )
                ->end()
            ->end()
        ->end();

        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function localizationsTree()
    {
        return (new TreeBuilder())->root('localizations')
                ->prototype('scalar')
            ->end();
    }

    /**
     * @return ArrayNodeDefinition
     */
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

    /**
     * @return ArrayNodeDefinition
     */
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

    /**
     * @return ArrayNodeDefinition
     */
    private function getDeserializationEntity()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('deserialization_parameters');

        $node->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('search_response_entity')
                        ->defaultValue(SearchResponse::class)
                    ->end()
                    ->scalarNode('suggestion_response_entity')
                        ->defaultValue(SuggestionResponse::class)
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
