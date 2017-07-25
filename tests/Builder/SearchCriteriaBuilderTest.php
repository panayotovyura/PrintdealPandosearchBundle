<?php

namespace Tests\Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Builder\SearchCriteriaBuilder;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;

class SearchCriteriaBuilderTest extends AbstractBuilderTest
{
    /**
     * @return SearchCriteriaBuilder
     */
    private function getBuilder()
    {
        return new SearchCriteriaBuilder(self::$serializer);
    }

    /**
     * @param SearchCriteria $criteria
     * @param array $array
     * @dataProvider criteriaProvider
     */
    public function testBuild(SearchCriteria $criteria, array $array)
    {
        $this->assertEquals($array, $this->getBuilder()->build($criteria));
    }

    public function criteriaProvider()
    {
        return [
            [
                new SearchCriteria(),
                [
                    'size' => 10,
                    'page' => 1,
                    'full' => false,
                    'nocorrect' => true,
                    'notiming' => false,
                    'track' => true,
                    'facets' => [],
                    'sort' => 'relevancy',
                ]
            ],
            [
                (new SearchCriteria())->setQuery('facets test')
                    ->setFacets(['pages']),
                [
                    'q' => 'facets test',
                    'size' => 10,
                    'page' => 1,
                    'full' => false,
                    'nocorrect' => true,
                    'notiming' => false,
                    'track' => true,
                    'facets' => ['pages'],
                    'sort' => 'relevancy',
                ]
            ],
            [
                (new SearchCriteria())->setQuery('sort test')
                    ->setSort('name'),
                [
                    'q' => 'sort test',
                    'size' => 10,
                    'page' => 1,
                    'full' => false,
                    'nocorrect' => true,
                    'notiming' => false,
                    'track' => true,
                    'sort' => 'name',
                    'facets' => [],
                ]
            ],
            [
                (new SearchCriteria())->setQuery('test')
                    ->setSize(5)
                    ->setPage(2)
                    ->setFull(false)
                    ->setNoCorrect(false)
                    ->setNoTiming(false)
                    ->setTrack(true),
                [
                    'q' => 'test',
                    'size' => 5,
                    'page' => 2,
                    'full' => false,
                    'nocorrect' => false,
                    'notiming' => false,
                    'track' => true,
                    'facets' => [],
                    'sort' => 'relevancy',
                ]
            ],
        ];
    }
}
