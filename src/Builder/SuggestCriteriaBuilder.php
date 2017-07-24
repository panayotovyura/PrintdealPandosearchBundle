<?php

namespace Printdeal\PandosearchBundle\Builder;

use JMS\Serializer\ArrayTransformerInterface;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;

class SuggestCriteriaBuilder
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
     * @param SuggestCriteria $criteria
     * @return array
     */
    public function build(SuggestCriteria $criteria): array
    {
        return $this->serializer->toArray($criteria);
    }
}
