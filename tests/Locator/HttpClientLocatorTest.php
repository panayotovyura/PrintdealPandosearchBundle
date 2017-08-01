<?php

namespace Tests\Printdeal\PandosearchBundle\Locator;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\Exception\ClientNotFoundException;
use Printdeal\PandosearchBundle\Locator\HttpClientLocator;

class HttpClientLocatorTest extends TestCase
{
    /**
     * @return HttpClientLocator
     */
    private function getClientLocator(): HttpClientLocator
    {
        return new HttpClientLocator();
    }

    /**
     * @param string $localization
     * @param string $expectedMessage
     * @dataProvider localizationsProvider
     */
    public function testClientNotFound(string $localization, string $expectedMessage)
    {
        $this->expectException(ClientNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $this->getClientLocator()->getClient($localization);
    }

    public function localizationsProvider()
    {
        return [
            [
                'en',
                sprintf(ClientNotFoundException::MESSAGE_TEMPLATE, 'en')
            ],
            [
                'ch',
                sprintf(ClientNotFoundException::MESSAGE_TEMPLATE, 'ch')
            ],
        ];
    }

    public function testClientWithoutLocalizationNotFound()
    {
        $this->expectException(ClientNotFoundException::class);
        $this->expectExceptionMessage(sprintf(ClientNotFoundException::MESSAGE_TEMPLATE, 'default'));

        $this->getClientLocator()->getClient();
    }

    public function testClientAddedSuccessfully()
    {
        $localization = 'nl';

        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientLocator = $this->getClientLocator();
        $clientLocator->addHttpClient($localization, $clientMock);

        $this->assertEquals($clientMock, $clientLocator->getClient($localization));
    }

    public function testClientForSingleLanguageFound()
    {
        $localization = 'default';

        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientLocator = $this->getClientLocator();
        $clientLocator->addHttpClient($localization, $clientMock);

        $this->assertEquals($clientMock, $clientLocator->getClient('nl'));
    }
}
