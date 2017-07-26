<?php

namespace Printdeal\PandosearchBundle\Locator;

use GuzzleHttp\ClientInterface;
use Printdeal\PandosearchBundle\Exception\ClientNotFoundException;

class GuzzleClientLocator
{
    private $clients = [];

    /**
     * @param string $localization
     * @param ClientInterface $client
     */
    public function addHttpClient(string $localization, ClientInterface $client) {
        $this->clients[$localization] = $client;
    }

    /**
     * @param string $localization
     * @return mixed
     * @throws ClientNotFoundException
     */
    public function getClient(string $localization = 'default'): ClientInterface
    {
        if (isset($this->clients[$localization])) {
            return $this->clients[$localization];
        }

        throw new ClientNotFoundException($localization);
    }
}
