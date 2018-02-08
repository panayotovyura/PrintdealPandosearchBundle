<?php

namespace Tests\Printdeal\PandosearchBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\DependencyInjection\Configuration;
use Printdeal\PandosearchBundle\Entity\Search\DefaultResponse as SearchDefaultResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\DefaultResponse as SuggestionDefaultResponse;
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
                    ],
                    'localizations' => [],
                    'search' => [
                        'host' => 'search.enrise.com',
                        'protocol' => 'https',
                    ],
                    'deserialization_parameters' => [
                        'search_response_entity' => SearchDefaultResponse::class,
                        'suggestion_response_entity' => SuggestionDefaultResponse::class
                    ]
                ],
            ],
            [
                [
                    'company_name' => 'drukwerkdeal.nl',
                    'localizations' => ['nl', 'en']
                ],
                [
                    'company_name' => 'drukwerkdeal.nl',
                    'guzzle_client' => [
                        'timeout' => 15,
                        'connect_timeout' => 2,
                    ],
                    'localizations' => ['nl', 'en'],
                    'search' => [
                        'host' => 'search.enrise.com',
                        'protocol' => 'https',
                    ],
                    'deserialization_parameters' => [
                        'search_response_entity' => SearchDefaultResponse::class,
                        'suggestion_response_entity' => SuggestionDefaultResponse::class
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
                    ],
                    'search' => [
                        'protocol' => 'http',
                        'host' => 'google.com',
                    ],
                    'deserialization_parameters' => [
                        'search_response_entity' => 'customEntity',
                        'suggestion_response_entity' => 'customEntity'
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
                    'localizations' => [],
                    'search' => [
                        'protocol' => 'http',
                        'host' => 'google.com',
                    ],
                    'deserialization_parameters' => [
                        'search_response_entity' => 'customEntity',
                        'suggestion_response_entity' => 'customEntity'
                    ]
                ],
            ],
        ];
    }
}
