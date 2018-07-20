<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Criteria\SerializableInterface;

class SearchCriteriaBuilder extends BaseQueryBuilder implements BuilderInterface
{
    /**
     * @param SerializableInterface $object
     * @return bool
     */
    public function supports(SerializableInterface $object): bool
    {
        return $object instanceof SearchCriteria;
    }
}
