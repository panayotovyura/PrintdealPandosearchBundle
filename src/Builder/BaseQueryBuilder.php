<?php

namespace Printdeal\PandosearchBundle\Builder;

use JMS\Serializer\ArrayTransformerInterface;
use Printdeal\PandosearchBundle\Criteria\SerializableInterface;

abstract class BaseQueryBuilder
{
        /**
        * @var ArrayTransformerInterface
        */
        protected $serializer;

        /**
        * @var array
        */
        private $overrides;

    /**
    * SearchCriteriaBuilder constructor.
    * @param ArrayTransformerInterface $serializer
    * @param array $queryOverrides
    */
    public function __construct(ArrayTransformerInterface $serializer, array $queryOverrides = [])
    {
        $this->serializer = $serializer;
        $this->overrides = $queryOverrides;
    }

    /**
    * @param array $query
    * @return array
    */
    protected function executeOverrides(array $query): array
    {
        return array_merge($this->overrides, $query);
    }

    /**
    * @param SerializableInterface $criteria
    * @return array
    */
    protected function buildSerializableObject(SerializableInterface $criteria): array
    {
        return  $this->executeOverrides(
            $this->serializer->toArray($criteria)
        );
    }
}
