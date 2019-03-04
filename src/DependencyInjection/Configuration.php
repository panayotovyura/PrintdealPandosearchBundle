<?php

namespace Printdeal\PandosearchBundle\DependencyInjection;

use Printdeal\PandosearchBundle\Entity\Search\DefaultResponse as SearchResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\DefaultResponse as SuggestionResponse;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('printdeal_pandosearch', 'array');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('company_name')
                    ->info('Company domain search provided for.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        $this->addHostSettings($rootNode);
        $this->addLocalizationsTree($rootNode);
        $this->addDefaultQueryOptions($rootNode);
        $this->addGuzzleSettings($rootNode);
        $this->addDeserializationEntity($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addHostSettings(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('search')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('protocol')
                            ->defaultValue('https')
                        ->end()
                        ->scalarNode('host')
                            ->defaultValue('search.enrise.com')
                            ->info('Search API host. Can be used to activate search at Enrise acceptance environment.')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addLocalizationsTree(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('localizations')
                    ->prototype('scalar')
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addDefaultQueryOptions(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('query_settings')
                    ->children()
                        ->booleanNode('track')->end()
                        ->booleanNode('full')->end()
                        ->booleanNode('nocorrect')->end()
                        ->booleanNode('notiming')->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addGuzzleSettings(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
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
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addDeserializationEntity(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('deserialization_parameters')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('search_response_entity')
                                ->defaultValue(SearchResponse::class)
                            ->end()
                            ->scalarNode('suggestion_response_entity')
                                ->defaultValue(SuggestionResponse::class)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
