<?php

namespace Printdeal\PandosearchBundle\Service;

use GuzzleHttp\ClientInterface;
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
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SearchService constructor.
     * @param ClientInterface $httpClient
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SuggestCriteriaBuilder $suggestCriteriaBuilder
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ClientInterface $httpClient,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SuggestCriteriaBuilder $suggestCriteriaBuilder,
        SerializerInterface $serializer
    ) {
        $this->httpClient = $httpClient;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->suggestCriteriaBuilder = $suggestCriteriaBuilder;
        $this->serializer = $serializer;
    }

    /**
     * @param SearchCriteria $criteria
     * @return SearchResponse
     * @throws RequestException
     * @throws SerializationException
     */
    public function search(SearchCriteria $criteria): SearchResponse
    {
        return $this->getResponse(
            self::SEARCH_ENDPOINT,
            $this->searchCriteriaBuilder->build($criteria),
            SearchResponse::class
        );
    }

    /**
     * @param SuggestCriteria $criteria
     * @return SuggestionResponse
     * @throws RequestException
     * @throws SerializationException
     */
    public function suggest(SuggestCriteria $criteria): SuggestionResponse
    {
        return $this->getResponse(
            self::SUGGEST_ENDPOINT,
            $this->suggestCriteriaBuilder->build($criteria),
            SuggestionResponse::class
        );
    }

    /**
     * @param string $url
     * @param array $query
     * @param string $deserializationType
     * @return array|SearchResponse|SuggestionResponse
     * @throws RequestException
     * @throws SerializationException
     */
    private function getResponse(
        string $url,
        array $query,
        string $deserializationType = self::DEFAULT_RETURN_TYPE
    ) {
        try {
            $response = $this->httpClient->request(
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
