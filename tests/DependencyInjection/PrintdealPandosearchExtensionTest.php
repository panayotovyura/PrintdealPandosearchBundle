<?php

namespace Tests\Printdeal\PandosearchBundle\DependencyInjection;

use Csa\Bundle\GuzzleBundle\DependencyInjection\CsaGuzzleExtension;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\DependencyInjection\Compiler\HttpClientsPass;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class PrintdealPandosearchExtensionTest extends TestCase
{
    /**
     * @param array $localization
     * @param array $expectedServices
     * @dataProvider localizationsProvider
     */
    public function testWithLanguages(array $localization, array $expectedServices)
    {
        $container = $this->createContainer();
        $container->registerExtension(new CsaGuzzleExtension());
        $container->registerExtension(new PrintdealPandosearchExtension());
        $container->loadFromExtension('printdeal_pandosearch', [
            'localizations' => $localization
        ]);
        $this->compileContainer($container);

        foreach ($expectedServices as $expectedService) {
            static::assertTrue($container->hasDefinition($expectedService['service_id']));
            static::assertBaseUri(
                $expectedService['base_uri'],
                $container->getDefinition($expectedService['service_id'])
            );
        }
    }

    public function localizationsProvider()
    {
        return [
            [
                ['nl', 'fr'],
                [
                    [
                        'service_id' => sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'nl'),
                        'base_uri' => 'https://search.enrise.com/drukwerkdeal.nl-nl/',
                    ],
                    [
                        'service_id' => sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'fr'),
                        'base_uri' => 'https://search.enrise.com/drukwerkdeal.nl-fr/',
                    ],

                ]
            ],
            [
                [],
                [
                    [
                        'service_id' => sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'default'),
                        'base_uri' => 'https://search.enrise.com/drukwerkdeal.nl/',
                    ]
                ],
            ]
        ];
    }

    /**
     * @param array $localizations
     * @param array $expectedServices
     * @dataProvider localizationsProvider
     */
    public function testWithLanguagesFromParameter(
        array $localizations,
        array $expectedServices
    ) {
        $parameterName = 'parameter.printdeal.localization';

        $container = $this->createContainer();
        $container->registerExtension(new CsaGuzzleExtension());
        $container->registerExtension(new PrintdealPandosearchExtension());
        $container->setParameter($parameterName, $localizations);
        $container->loadFromExtension('printdeal_pandosearch', [
            'localizations' => '%' . $parameterName . '%',
        ]);
        $this->compileContainer($container);

        foreach ($expectedServices as $expectedService) {
            static::assertTrue($container->hasDefinition($expectedService['service_id']));
            static::assertBaseUri(
                $expectedService['base_uri'],
                $container->getDefinition($expectedService['service_id'])
            );
        }
    }

    /**
     * @param string $alias
     * @dataProvider aliasesProvider
     */
    public function testAliasExist(string $alias)
    {
        $container = $this->createContainer();
        $container->registerExtension(new PrintdealPandosearchExtension());
        $container->loadFromExtension('printdeal_pandosearch', []);
        $this->compileContainer($container);

        static::assertTrue(
            $container->hasAlias($alias)
        );
    }

    /**
     * @return array
     */
    public function aliasesProvider()
    {
        return [
            ['printdeal_pandosearch'],
        ];
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__.'/Fixtures',
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => true,
            'kernel.bundles' => [
                'PrintdealPandosearchBundle' => 'Printdeal\\PandosearchBundle\\PrintdealPandosearchBundle',
                'CsaGuzzleBundle' => 'Csa\\Bundle\\GuzzleBundle\\CsaGuzzleBundle',
            ],

        ]));
        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->prependExtensionConfig('printdeal_pandosearch', [
            'company_name' => 'drukwerkdeal.nl',
            'query_settings' => [],
            'guzzle_client' => [
                'timeout' => 15,
                'connect_timeout' => 2,
            ],
            'search' => [
                'protocol' => 'https',
                'host' => 'search.enrise.com',
            ]
        ]);
        $container->compile();
    }

    public function testArrayPrependedToBuilder()
    {
        $queryOverride = [
            'track' => false,
            'full' => false,
            'nocorrect' => false,
            'notiming' => false,
        ];
        $container = $this->createContainer();
        $container->registerExtension(new PrintdealPandosearchExtension());
        $container->loadFromExtension('printdeal_pandosearch', [
            'query_settings' => $queryOverride,
            'localizations' => ['nl', 'fr'],
        ]);
        $this->compileContainer($container);

        $builderIds = array_keys($container->findTaggedServiceIds('printdeal.pandosearch.builder'));
        foreach ($builderIds as $builderId) {
            $this->assertEquals($queryOverride, $container->getDefinition($builderId)->getArgument(1));
        }
    }

    /**
     * @param string $expected
     * @param Definition $definition
     */
    private static function assertBaseUri(string $expected, Definition $definition)
    {
        static::assertEquals($expected, (string)($definition->getArgument(0)['base_uri']));
    }
}
