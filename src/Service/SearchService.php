<?php

namespace Printdeal\PandosearchBundle\Service;

use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\SerializerInterface;
use Printdeal\PandosearchBundle\Builder\SearchCriteriaBuilder;
use Printdeal\PandosearchBundle\Builder\SuggestCriteriaBuilder;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;
use Printdeal\PandosearchBundle\Entity\Search\Response as SearchResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\Response as SuggestionResponse;
use Printdeal\PandosearchBundle\Exception\RequestException;
use Printdeal\PandosearchBundle\Exception\SerializationException;
use Printdeal\PandosearchBundle\Locator\HttpClientLocator;

class SearchService
{
    const SUGGEST_ENDPOINT = 'suggest';
    const SEARCH_ENDPOINT = 'search';

    const GET_METHOD = 'GET';

    const JSON_ACCEPT = 'application/json';

    const DEFAULT_RESPONSE_FORMAT = 'json';

    const DEFAULT_RETURN_TYPE = 'array';

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SuggestCriteriaBuilder
     */
    private $suggestCriteriaBuilder;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var HttpClientLocator
     */
    private $clientLocator;

    /**
     * SearchService constructor.
     * @param HttpClientLocator $clientLocator
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SuggestCriteriaBuilder $suggestCriteriaBuilder
     * @param SerializerInterface $serializer
     */
    public function __construct(
        HttpClientLocator $clientLocator,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SuggestCriteriaBuilder $suggestCriteriaBuilder,
        SerializerInterface $serializer
    ) {
        $this->clientLocator = $clientLocator;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->suggestCriteriaBuilder = $suggestCriteriaBuilder;
        $this->serializer = $serializer;
    }

    /**
     * @param SearchCriteria $criteria
     * @param string $localization
     * @return SearchResponse
     */
    public function search(SearchCriteria $criteria, string $localization = 'default'): SearchResponse
    {
        return $this->getResponse(
            self::SEARCH_ENDPOINT,
            $localization,
            $this->searchCriteriaBuilder->build($criteria),
            SearchResponse::class
        );
    }

    /**
     * @param SuggestCriteria $criteria
     * @param string $localization
     * @return SuggestionResponse
     */
    public function suggest(SuggestCriteria $criteria, string $localization = 'default'): SuggestionResponse
    {
        return $this->getResponse(
            self::SUGGEST_ENDPOINT,
            $localization,
            $this->suggestCriteriaBuilder->build($criteria),
            SuggestionResponse::class
        );
    }

    /**
     * @param string $url
     * @param string $localization
     * @param array $query
     * @param string $deserializationType
     * @return array|SearchResponse|SuggestionResponse
     * @throws RequestException
     * @throws SerializationException
     */
    private function getResponse(
        string $url,
        string $localization,
        array $query,
        string $deserializationType = self::DEFAULT_RETURN_TYPE
    ) {
        try {
            $response = $this->clientLocator->getClient($localization)->request(
                self::GET_METHOD,
                $url,
                [
                    'query' => $query,
                    'headers' => [
                        'accept' => self::JSON_ACCEPT,
                    ]
                ]
            );
        } catch (TransferException $exception) {
            throw new RequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        try {
            return $this->serializer->deserialize(
                $response->getBody()->getContents(),
                $deserializationType,
                self::DEFAULT_RESPONSE_FORMAT
            );
        } catch (\Exception $exception) {
            throw new SerializationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
