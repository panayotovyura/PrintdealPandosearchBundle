<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SuggestCriteria;

class SuggestCriteriaBuilder extends BaseQueryBuilder
{
    /**
     * @param SuggestCriteria $criteria
     * @return array
     */
    public function build(SuggestCriteria $criteria): array
    {
        return parent::buildSerializableObject($criteria);
    }
}
