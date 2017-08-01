<?php

namespace Printdeal\PandosearchBundle\Locator;

use GuzzleHttp\ClientInterface;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Printdeal\PandosearchBundle\Exception\ClientNotFoundException;

class HttpClientLocator
{
    const DEFAULT_GUZZLE_CLIENT = PrintdealPandosearchExtension::DEFAULT_GUZZLE_CLIENT_SUFFIX;

    private $clients = [];

    /**
     * @param string $localization
     * @param ClientInterface $client
     */
    public function addHttpClient(string $localization, ClientInterface $client)
    {
        $this->clients[$localization] = $client;
    }

    /**
     * @param string $localization
     * @return ClientInterface
     * @throws ClientNotFoundException
     */
    public function getClient(string $localization = self::DEFAULT_GUZZLE_CLIENT): ClientInterface
    {
        if (isset($this->clients[$localization])) {
            return $this->clients[$localization];
        }

        if (count($this->clients) === 1) {
            return reset($this->clients);
        }

        throw new ClientNotFoundException($localization);
    }
}
