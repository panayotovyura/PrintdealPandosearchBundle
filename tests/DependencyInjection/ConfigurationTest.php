<?php

namespace Tests\Printdeal\PandosearchBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider configurationProvider
     *
     * @param array $input
     * @param array $output
     */
    public function testConfiguration($input, $output)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$input]);

        static::assertEquals(
            $output,
            $config
        );
    }

    public function configurationProvider()
    {
        return [
            [
                [
                    'company_name' => 'drukwerkdeal.nl',
                ],
                [
                    'company_name' => 'drukwerkdeal.nl',
                    'guzzle_client' => [
                        'timeout' => 15,
                        'connect_timeout' => 2,
                    ]
                ],
            ],
            [
                [
                    'company_name' => 'drukwerkdeal.nl',
                    'guzzle_client' => [
                        'timeout' => 30,
                        'connect_timeout' => 5,
                    ],
                    'query_settings' => [
                        'track' => false,
                    ]
                ],
                [
                    'company_name' => 'drukwerkdeal.nl',
                    'guzzle_client' => [
                        'timeout' => 30,
                        'connect_timeout' => 5,
                    ],
                    'query_settings' => [
                        'track' => false,
                    ],
                ],
            ],
        ];
    }
}
