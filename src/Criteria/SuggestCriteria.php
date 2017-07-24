<?php

namespace Printdeal\PandosearchBundle\Criteria;

use JMS\Serializer\Annotation as Serializer;

final class SuggestCriteria
{
    /**
     * @var string
     * @Serializer\SerializedName("q")
     * @Serializer\Type("string")
     */
    private $query;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    private $track = true;

    /**
     * @param string $query
     * @return SuggestCriteria
     */
    public function setQuery(string $query): SuggestCriteria
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param bool $track
     * @return SuggestCriteria
     */
    public function setTrack(bool $track): SuggestCriteria
    {
        $this->track = $track;
        return $this;
    }
}
