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
    const LOCALIZED_URL_TEMPLATE = self::BASE_URL_TEMPLATE . '%s/';

    const GUZZLE_CLIENT_NAME = 'printdeal.pandosearch_client.%s';

    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIGS_PATH));
        $loader->load('services.yml');

        if (!empty($mergedConfig['query_settings'])) {
            $builderIds = array_keys($container->findTaggedServiceIds('printdeal.pandosearch.builder'));
            foreach ($builderIds as $builderId) {
                $searchServiceDefinition = $container->getDefinition($builderId);
                $searchServiceDefinition->replaceArgument(1, $mergedConfig['query_settings']);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['CsaGuzzleBundle'])) {
            $container->prependExtensionConfig('csa_guzzle', [
                'clients' => $this->getClientsConfiguration($container),
            ]);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @return array
     */
    private function getClientsConfiguration(ContainerBuilder $container)
    {
        $config = $this->getConfig($container);
        $localizations = $config['localizations'] ?? [];
        $companyName = (string)$config['company_name'];
        $guzzleConfig = $config['guzzle_client'];
        if (!$localizations) {
            return [
                sprintf(self::GUZZLE_CLIENT_NAME, 'default') => [
                    'config' => $this->getClientConfiguration(
                        $companyName,
                        $guzzleConfig
                    ),
                ]
            ];
        }

        $clients = [];
        foreach ($localizations as $localization) {
            $clients = array_merge(
                $clients,
                [
                    sprintf(self::GUZZLE_CLIENT_NAME, $localization) => [
                        'config' => $this->getClientConfiguration(
                            $companyName,
                            $guzzleConfig,
                            $localization
                        ),
                    ]
                ]
            );
        }

        return $clients;
    }

    /**
     * @param ContainerBuilder $container
     * @return array
     */
    private function getConfig(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('printdeal_pandosearch');
        $config = [];
        // let resources override the previous set value
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
        return $config;
    }

    /**
     * @param string $companyName
     * @param array $config
     * @param string $localization
     * @return array
     */
    private function getClientConfiguration(string $companyName, array $config, string $localization = ''): array
    {
        return [
            'timeout' => $config['timeout'] ?? self::DEFAULT_GUZZLE_TIMEOUT,
            'connect_timeout' => $config['connect_timeout'] ?? self::DEFAULT_GUZZLE_CONNECT_TIMEOUT,
            'base_uri' => $this->getBaseUrl($companyName, $localization),
        ];
    }

    /**
     * @param string $companyName
     * @param string $localization
     * @return string
     */
    private function getBaseUrl(string $companyName, string $localization): string
    {
        return $localization ? sprintf(self::LOCALIZED_URL_TEMPLATE, $companyName, $localization) :
            sprintf(self::BASE_URL_TEMPLATE, $companyName);
    }
}
