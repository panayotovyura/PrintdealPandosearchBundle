<?php

namespace Tests\Printdeal\PandosearchBundle\DependencyInjection;

use Csa\Bundle\GuzzleBundle\DependencyInjection\CsaGuzzleExtension;
use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class PrintdealPandosearchExtensionTest extends TestCase
{
    /**
     * @dataProvider loadConfigurationProvider
     *
     * @param array $services
     */
    public function testLoadConfiguration($services)
    {
        $container = $this->createContainer();
        $container->registerExtension(new CsaGuzzleExtension());
        $container->registerExtension(new PrintdealPandosearchExtension());
        $container->loadFromExtension('printdeal_pandosearch', []);
        $this->compileContainer($container);

        foreach ($services as $service) {
            static::assertTrue(
                $container->hasDefinition($service)
            );
        }
    }

    public function loadConfigurationProvider()
    {
        return [
            [
                [
                    'csa_guzzle.client.printdeal.pandosearch_client'
                ],
            ],
        ];
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
