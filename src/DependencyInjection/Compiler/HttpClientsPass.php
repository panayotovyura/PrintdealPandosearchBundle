<?php

namespace Printdeal\PandosearchBundle\DependencyInjection\Compiler;

use Csa\Bundle\GuzzleBundle\DependencyInjection\CompilerPass\MiddlewarePass;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Printdeal\PandosearchBundle\Locator\GuzzleClientLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HttpClientsPass implements CompilerPassInterface
{
    const GUZZLE_CLIENTS_SERVICES_NAME = 'csa_guzzle.client.' . PrintdealPandosearchExtension::GUZZLE_CLIENT_NAME;

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(GuzzleClientLocator::class)) {
            return;
        }
        $locatorService = $container->findDefinition(GuzzleClientLocator::class);

        $guzzleClientNamePattern = sprintf(self::GUZZLE_CLIENTS_SERVICES_NAME, '');

        $clients = array_keys($container->findTaggedServiceIds(MiddlewarePass::CLIENT_TAG));
        foreach ($clients as $clientId) {
            if (strpos($clientId, $guzzleClientNamePattern)
                !== false) {
                $locatorService->addMethodCall(
                    'addHttpClient',
                    [str_replace($guzzleClientNamePattern, '', $clientId), new Reference($clientId)]
                );
            }
        }
    }
}
