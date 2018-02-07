<?php

namespace Tests\Printdeal\PandosearchBundle\DeserializationHandler;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use Printdeal\PandosearchBundle\DeserializationHandler\SearchResponseDeserializationHandler;
use Printdeal\PandosearchBundle\DeserializationHandler\SuggestionResponseDeserializationHandler;
use Printdeal\PandosearchBundle\Entity\Search\DefaultResponse;
use Printdeal\PandosearchBundle\Entity\Search\Response as SearchResponse;
use Printdeal\PandosearchBundle\Entity\Suggestion\Response as SuggestionResponse;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class SearchResponseDeserializationHandlerTest extends TestCase
{
    /**
     * @param array $expectedResult
     * @dataProvider searchSubscribingMethodsDataProvider
     */
    public function testGetSearchSubscribingMethods(array $expectedResult) {
        $result = SearchResponseDeserializationHandler::getSubscribingMethods();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function searchSubscribingMethodsDataProvider(): array
    {
        return [
            [
                [[
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'format' => 'json',
                    'type' => SearchResponse::class,
                    'method' => 'deserializeResponse',
                ]]
            ],
        ];
    }

    /**
     * @param array $expectedResult
     * @dataProvider suggestionSubscribingMethodsDataProvider
     */
    public function testGetSuggestionSubscribingMethods(array $expectedResult) {
        $result = SuggestionResponseDeserializationHandler::getSubscribingMethods();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function suggestionSubscribingMethodsDataProvider(): array
    {
        return [
            [
                [[
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'format' => 'json',
                    'type' => SuggestionResponse::class,
                    'method' => 'deserializeResponse',
                ]]
            ],
        ];
    }

    public function testDeserializeResponse()
    {
        $data = [
            'total' => 154,
            'hits' => []
        ];

        $serializer = $this->getMockBuilder(ArrayTransformerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serializer->expects($this->once())
            ->method('fromArray')
            ->with($data, DefaultResponse::class)
            ->will($this->returnValue(new DefaultResponse()));
        $visitor = $this->getMockBuilder(JsonDeserializationVisitor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $handler = new SearchResponseDeserializationHandler(DefaultResponse::class, $serializer);
        $result = $handler->deserializeResponse($visitor, $data);
        $this->assertInstanceOf(DefaultResponse::class, $result);
    }
}
