<?php

namespace Tests\Printdeal\PandosearchBundle\DependencyInjection;

use Csa\Bundle\GuzzleBundle\DependencyInjection\CsaGuzzleExtension;
use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\DependencyInjection\Compiler\HttpClientsPass;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
            static::assertTrue($container->hasDefinition($expectedService));
        }
    }

    public function localizationsProvider()
    {
        return [
            [
                ['nl', 'fr'],
                [
                    sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'nl'),
                    sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'fr')
                ]
            ],
            [
                [],
                [sprintf(HttpClientsPass::GUZZLE_CLIENTS_SERVICES_NAME, 'default')]
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
            static::assertTrue($container->hasDefinition($expectedService));
        }
    }

    /**
     * @param $alias
     * @dataProvider aliasesProvider
     */
    public function testAliasExist($alias)
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
}
