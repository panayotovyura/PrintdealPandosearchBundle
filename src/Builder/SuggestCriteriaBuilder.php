<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SerializableInterface;
use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;

class SuggestCriteriaBuilder extends BaseQueryBuilder implements BuilderInterface
{
    /**
     * @param SerializableInterface $object
     * @return bool
     */
    public function supports(SerializableInterface $object): bool
    {
        return $object instanceof SuggestCriteria;
    }
}
