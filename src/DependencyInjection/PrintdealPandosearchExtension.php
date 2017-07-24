<?php

namespace Printdeal\PandosearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class PrintdealPandosearchExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    const CONFIGS_PATH = __DIR__.'/../Resources/config';
    const DEFAULT_GUZZLE_TIMEOUT = 15;
    const DEFAULT_GUZZLE_CONNECT_TIMEOUT = 2;
    const BASE_URL_TEMPLATE = 'https://search.enrise.com/%s/';

    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIGS_PATH));
        $loader->load('services.yml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['CsaGuzzleBundle'])) {
            $container->prependExtensionConfig('csa_guzzle', [
                'clients' => [
                    'printdeal.pandosearch_client' => [
                        'config' => $this->getClientConfiguration(
                            $container->getExtensionConfig('printdeal_pandosearch')
                        ),
                    ]
                ]
            ]);
        }
    }

    /**
     * @param array $configs
     * @return array
     */
    private function getClientConfiguration(array $configs)
    {
        $config = [];
        // let resources override the previous set value
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        return [
            'timeout' => $config['guzzle_client']['timeout'] ?? self::DEFAULT_GUZZLE_TIMEOUT,
            'connect_timeout' => $config['guzzle_client']['connect_timeout'] ?? self::DEFAULT_GUZZLE_CONNECT_TIMEOUT,
            'base_uri' => sprintf(self::BASE_URL_TEMPLATE, $config['company_name']),
        ];
    }
}
