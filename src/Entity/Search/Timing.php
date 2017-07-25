<?php

namespace Printdeal\PandosearchBundle\Entity\Search;

use JMS\Serializer\Annotation as Serializer;

class Timing
{
    /**
     * @var float
     * @Serializer\Type("float")
     */
    private $search;

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\SerializedName("search:took")
     */
    private $searchTook;

    /**
     * @var float
     * @Serializer\Type("float")
     */
    private $request;

    /**
     * @return float
     */
    public function getSearch(): float
    {
        return $this->search;
    }

    /**
     * @return float
     */
    public function getSearchTook(): float
    {
        return $this->searchTook;
    }

    /**
     * @return float
     */
    public function getRequest(): float
    {
        return $this->request;
    }
}
