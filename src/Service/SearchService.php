<?php

namespace Printdeal\PandosearchBundle\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\SerializerInterface;
use Printdeal\PandosearchBundle\Builder\SearchCriteriaBuilder;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Exception\RequestException;
use Printdeal\PandosearchBundle\Exception\SerializationException;

class SearchService
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $criteriaBuilder;

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
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ClientInterface $httpClient,
        SearchCriteriaBuilder $criteriaBuilder,
        SerializerInterface $serializer
    ) {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->httpClient = $httpClient;
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
                'GET',
                'search',
                [
                    'query' => $this->criteriaBuilder->build($criteria),
                    'headers' => [
                        'accept' => 'application/json',
                    ]
                ]
            );
        } catch (TransferException $exception) {
            throw new RequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        try {
            return $this->serializer->deserialize($response->getBody()->getContents(), 'json', 'array');
        } catch (\Exception $exception) {
            throw new SerializationException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
