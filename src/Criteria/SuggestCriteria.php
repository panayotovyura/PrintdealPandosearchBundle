<?php

namespace Printdeal\PandosearchBundle\Criteria;

use JMS\Serializer\Annotation as Serializer;

final class SuggestCriteria implements SerializableInterface
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
    private $track;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $size = 10;

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

    /**
     * @param int $size
     * @return SuggestCriteria
     */
    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }
}
