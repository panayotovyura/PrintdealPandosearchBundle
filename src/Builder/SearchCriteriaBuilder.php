<?php

namespace Printdeal\PandosearchBundle\Builder;

use JMS\Serializer\ArrayTransformerInterface;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;

class SearchCriteriaBuilder
{
    /**
     * @var ArrayTransformerInterface
     */
    private $serializer;

    /**
     * SearchCriteriaBuilder constructor.
     * @param ArrayTransformerInterface $serializer
     */
    public function __construct(ArrayTransformerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param SearchCriteria $criteria
     * @return array
     */
    public function build(SearchCriteria $criteria): array
    {
        return $this->serializer->toArray($criteria);
    }
}
