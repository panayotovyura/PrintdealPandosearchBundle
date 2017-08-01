<?php

namespace Printdeal\PandosearchBundle\Locator;

use GuzzleHttp\ClientInterface;
use Printdeal\PandosearchBundle\DependencyInjection\PrintdealPandosearchExtension;
use Printdeal\PandosearchBundle\Exception\ClientNotFoundException;
use Printdeal\PandosearchBundle\Exception\UnknownLocalizationException;

class HttpClientLocator
{
    const DEFAULT_GUZZLE_CLIENT = PrintdealPandosearchExtension::DEFAULT_GUZZLE_CLIENT_SUFFIX;

    /**
     * @var ClientInterface[]
     */
    private $clients = [];

    /**
     * @var array
     */
    private $localizations;

    /**
     * HttpClientLocator constructor.
     * @param array $localizations
     */
    public function __construct(array $localizations = [])
    {
        $this->localizations = $localizations;
    }

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
     * @throws UnknownLocalizationException
     */
    public function getClient(string $localization = self::DEFAULT_GUZZLE_CLIENT): ClientInterface
    {
        if ($this->localizations && !in_array($localization, $this->localizations, true)) {
            throw new UnknownLocalizationException($localization);
        }

        if (isset($this->clients[$localization])) {
            return $this->clients[$localization];
        }

        if (count($this->clients) === 1) {
            return reset($this->clients);
        }

        throw new ClientNotFoundException($localization);
    }
}
