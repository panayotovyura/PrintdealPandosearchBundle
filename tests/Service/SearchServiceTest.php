<?php

namespace Tests\Printdeal\PandosearchBundle\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as Mock;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;
use Printdeal\PandosearchBundle\Exception\ClientNotFoundException;
use Printdeal\PandosearchBundle\Exception\RequestException;
use Printdeal\PandosearchBundle\Exception\SerializationException;
use Printdeal\PandosearchBundle\Locator\HttpClientLocator;
use Printdeal\PandosearchBundle\Service\QueryBuilder;
use Printdeal\PandosearchBundle\Service\SearchService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Printdeal\PandosearchBundle\Entity\Search\Response as SearchResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\Response as SuggestionResponse;

class SearchServiceTest extends TestCase
{
    /**
     * @param HttpClientLocator|Mock $clientLocator
     * @param QueryBuilder|Mock $queryBuilder
     * @param SerializerInterface|Mock $serializer
     * @return SearchService
     */
    private function getSearchServiceMock(
        Mock $clientLocator = null,
        Mock $queryBuilder = null,
        Mock $serializer = null
    ) {
        if (is_null($clientLocator)) {
            /** @var HttpClientLocator $clientLocator */
            $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
                ->disableOriginalConstructor()
                ->getMock();
        }

        if (is_null($queryBuilder)) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();
        }

        if (is_null($serializer)) {
            /** @var SerializerInterface $serializer */
            $serializer = $this->getMockBuilder(SerializerInterface::class)
                ->disableOriginalConstructor()
                ->getMock();
        }

        return new SearchService($clientLocator, $queryBuilder, $serializer);
    }

    public function testSearchGuzzleError()
    {
        $criteria = new SearchCriteria();
        $criteriaArray = [
            'q' => 'some search query',
        ];

        /** @var QueryBuilder|Mock $criteriaBuilder */
        $criteriaBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $criteriaBuilder->expects($this->once())
            ->method('build')
            ->with($criteria)
            ->willReturn($criteriaArray);

        /** @var Mock|ClientException $guzzleException */
        $guzzleException = $this->getMockBuilder(ClientException::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ClientInterface|Mock $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                SearchService::GET_METHOD,
                SearchService::SEARCH_ENDPOINT,
                [
                    'query' => $criteriaArray,
                    'headers' => [
                        'accept' => SearchService::JSON_ACCEPT,
                    ]
                ]
            )->willThrowException($guzzleException);

        $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientLocator->expects($this->once())
            ->method('getClient')
            ->with('default')
            ->willReturn($httpClient);

        $this->expectException(RequestException::class);

        $this->getSearchServiceMock($clientLocator, $criteriaBuilder)->search($criteria);
    }

    public function testSearchSerializerError()
    {
        $criteria = new SearchCriteria();
        $criteriaArray = [
            'q' => 'some search query',
        ];

        /** @var QueryBuilder|Mock $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $searchCriteriaBuilder->expects($this->once())
            ->method('build')
            ->with($criteria)
            ->willReturn($criteriaArray);

        $searchResponse = 'someJson';

        $streamInterface = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $streamInterface->expects($this->once())
            ->method('getContents')
            ->willReturn($searchResponse);

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($streamInterface);

        /** @var ClientInterface|Mock $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                SearchService::GET_METHOD,
                SearchService::SEARCH_ENDPOINT,
                [
                    'query' => $criteriaArray,
                    'headers' => [
                        'accept' => SearchService::JSON_ACCEPT,
                    ]
                ]
            )->willReturn($response);

        $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientLocator->expects($this->once())
            ->method('getClient')
            ->with('default')
            ->willReturn($httpClient);

        /** @var SerializerInterface|Mock $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($searchResponse, SearchResponse::class, 'json')
            ->willThrowException(new UnsupportedFormatException());

        $this->expectException(SerializationException::class);

        $this->getSearchServiceMock(
            $clientLocator,
            $searchCriteriaBuilder,
            $serializer
        )->search($criteria);
    }

    public function testSearchSuccess()
    {
        $criteria = new SearchCriteria();
        $criteriaArray = [
            'q' => 'some search query',
        ];

        /** @var QueryBuilder|Mock $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $searchCriteriaBuilder->expects($this->once())
            ->method('build')
            ->with($criteria)
            ->willReturn($criteriaArray);

        $searchResponse = 'someJson';
        $searchResponseObject = $this->getMockBuilder(SearchResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamInterface = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $streamInterface->expects($this->once())
            ->method('getContents')
            ->willReturn($searchResponse);

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($streamInterface);

        /** @var ClientInterface|Mock $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                SearchService::GET_METHOD,
                SearchService::SEARCH_ENDPOINT,
                [
                    'query' => $criteriaArray,
                    'headers' => [
                        'accept' => SearchService::JSON_ACCEPT,
                    ]
                ]
            )->willReturn($response);

        $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientLocator->expects($this->once())
            ->method('getClient')
            ->with('default')
            ->willReturn($httpClient);

        /** @var SerializerInterface|Mock $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($searchResponse, SearchResponse::class, 'json')
            ->willReturn($searchResponseObject);

        $this->assertEquals($searchResponseObject, $this->getSearchServiceMock(
            $clientLocator,
            $searchCriteriaBuilder,
            $serializer
        )->search($criteria));
    }

    public function testSuggestionSuccess()
    {
        $criteria = new SuggestCriteria();
        $criteriaArray = [
            'q' => 'some search query',
        ];

        /** @var QueryBuilder|Mock $suggestCriteriaBuilder */
        $suggestCriteriaBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $suggestCriteriaBuilder->expects($this->once())
            ->method('build')
            ->with($criteria)
            ->willReturn($criteriaArray);

        $suggestionResponse = 'someJson';
        $suggestionResponseObject = $this->getMockBuilder(SuggestionResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamInterface = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $streamInterface->expects($this->once())
            ->method('getContents')
            ->willReturn($suggestionResponse);

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($streamInterface);

        /** @var ClientInterface|Mock $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                SearchService::GET_METHOD,
                SearchService::SUGGEST_ENDPOINT,
                [
                    'query' => $criteriaArray,
                    'headers' => [
                        'accept' => SearchService::JSON_ACCEPT,
                    ]
                ]
            )->willReturn($response);

        $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientLocator->expects($this->once())
            ->method('getClient')
            ->with('default')
            ->willReturn($httpClient);

        /** @var SerializerInterface|Mock $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($suggestionResponse, SuggestionResponse::class, 'json')
            ->willReturn($suggestionResponseObject);

        $this->assertEquals($suggestionResponseObject, $this->getSearchServiceMock(
            $clientLocator,
            $suggestCriteriaBuilder,
            $serializer
        )->suggest($criteria));
    }

    public function testClientNotFoundException()
    {
        $criteria = new SearchCriteria();
        $criteriaArray = [
            'q' => 'some search query',
        ];
        $localization = 'as';

        /** @var QueryBuilder|Mock $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $searchCriteriaBuilder->expects($this->once())
            ->method('build')
            ->with($criteria)
            ->willReturn($criteriaArray);

        /** @var ClientNotFoundException|Mock $exception */
        $exception = $this->getMockBuilder(ClientNotFoundException::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientLocator = $this->getMockBuilder(HttpClientLocator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientLocator->expects($this->once())
            ->method('getClient')
            ->with($localization)
            ->willThrowException($exception);

        $this->expectException(ClientNotFoundException::class);

        $this->getSearchServiceMock($clientLocator, $searchCriteriaBuilder)->search($criteria, $localization);
    }
}
