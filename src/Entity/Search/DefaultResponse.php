<?php

namespace Printdeal\PandosearchBundle\Entity\Search;

use JMS\Serializer\Annotation as Serializer;

class DefaultResponse extends Response
{
    /**
     * @var array
     * @Serializer\Type("array")
     */
    protected $hits = [];

    /**
     * @return array
     */
    public function getHits(): array
    {
        return $this->hits;
    }
}
