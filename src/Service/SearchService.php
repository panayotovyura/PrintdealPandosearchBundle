<?php

namespace Printdeal\PandosearchBundle\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\SerializerInterface;
use Printdeal\PandosearchBundle\Builder\SearchCriteriaBuilder;
use Printdeal\PandosearchBundle\Builder\SuggestCriteriaBuilder;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;
use Printdeal\PandosearchBundle\Exception\RequestException;
use Printdeal\PandosearchBundle\Exception\SerializationException;

class SearchService
{
    const SUGGEST_ENDPOINT = 'suggest';
    const SEARCH_ENDPOINT = 'search';

    const GET_METHOD = 'GET';

    const JSON_ACCEPT = 'application/json';

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
     * @return array
     * @throws RequestException
     * @throws SerializationException
     */
    public function search(SearchCriteria $criteria): array
    {
        try {
            $response = $this->httpClient->request(
                self::GET_METHOD,
                self::SEARCH_ENDPOINT,
                [
                    'query' => $this->searchCriteriaBuilder->build($criteria),
                    'headers' => [
                        'accept' => self::JSON_ACCEPT,
                    ]
                ]
            );
        } catch (TransferException $exception) {
            throw new RequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        try {
            return $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        } catch (\Exception $exception) {
            throw new SerializationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param SuggestCriteria $criteria
     * @return array
     * @throws RequestException
     * @throws SerializationException
     */
    public function suggest(SuggestCriteria $criteria): array
    {
        try {
            $response = $this->httpClient->request(
                self::GET_METHOD,
                self::SUGGEST_ENDPOINT,
                [
                    'query' => $this->suggestCriteriaBuilder->build($criteria),
                    'headers' => [
                        'accept' => self::JSON_ACCEPT,
                    ]
                ]
            );
        } catch (TransferException $exception) {
            throw new RequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        try {
            return $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        } catch (\Exception $exception) {
            throw new SerializationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
