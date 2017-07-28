<?php

namespace Printdeal\PandosearchBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

class Timing
{
    /**
     * @var float
     * @Serializer\Type("float")
     */
    private $search = 0.0;

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\SerializedName("search:took")
     */
    private $searchTook = 0.0;

    /**
     * @var float
     * @Serializer\Type("float")
     */
    private $request = 0.0;

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
