<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SearchCriteria;

class SearchCriteriaBuilder extends BaseQueryBuilder
{
    /**
     * @param SearchCriteria $criteria
     * @return array
     */
    public function build(SearchCriteria $criteria): array
    {
        return parent::buildSerializableObject($criteria);
    }
}
