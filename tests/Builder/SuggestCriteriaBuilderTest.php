<?php

namespace Tests\Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Builder\SuggestCriteriaBuilder;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;

class SuggestCriteriaBuilderTest extends AbstractBuilderTest
{
    /**
     * @return SuggestCriteriaBuilder
     */
    private function getBuilder()
    {
        return new SuggestCriteriaBuilder(self::$serializer);
    }

    /**
     * @param SuggestCriteria $criteria
     * @param array $array
     * @dataProvider criteriaProvider
     */
    public function testBuild(SuggestCriteria $criteria, array $array)
    {
        $this->assertEquals($array, $this->getBuilder()->build($criteria));
    }

    public function criteriaProvider()
    {
        return [
            [
                new SuggestCriteria(),
                [
                    'size' => 10
                ]
            ],
            [
                (new SuggestCriteria())->setTrack(false),
                [
                    'track' => false,
                    'size' => 10
                ]
            ],
            [
                (new SuggestCriteria())->setQuery('test query')
                    ->setTrack(false),
                [
                    'q' => 'test query',
                    'track' => false,
                    'size' => 10
                ]
            ],
            [
                (new SuggestCriteria())->setQuery('test query')
                    ->setTrack(false)
                    ->setSize(5),
                [
                    'q' => 'test query',
                    'track' => false,
                    'size' => 5
                ]
            ],
        ];
    }

    public function testSupport()
    {
        $this->assertTrue($this->getBuilder()->supports(new SuggestCriteria()));
    }
}
