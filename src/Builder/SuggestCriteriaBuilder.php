<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SerializableInterface;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;

class SuggestCriteriaBuilder extends BaseQueryBuilder implements BuilderInterface
{
    /**
     * @param SerializableInterface $criteria
     * @return array
     */
    public function build(SerializableInterface $criteria): array
    {
        return parent::buildSerializableObject($criteria);
    }

    /**
     * @param SerializableInterface $object
     * @return bool
     */
    public function supports(SerializableInterface $object): bool
    {
        return $object instanceof SuggestCriteria;
    }
}
