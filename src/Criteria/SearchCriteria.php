<?php

namespace Printdeal\PandosearchBundle\Criteria;

use JMS\Serializer\Annotation as Serializer;

final class SearchCriteria
{
    /**
     * @var string
     * @Serializer\SerializedName("q")
     * @Serializer\Type("string")
     */
    private $query;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $size = 10;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $page = 1;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    private $full = false;

    /**
     * @var bool
     * @Serializer\SerializedName("nocorrect")
     * @Serializer\Type("boolean")
     */
    private $noCorrect = true;

    /**
     * @var array<string, string>
     * @Serializer\Type("array<string, string>")
     */
    private $facets;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $sort;

    /**
     * @var bool
     * @Serializer\SerializedName("notiming")
     * @Serializer\Type("boolean")
     */
    private $noTiming = false;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    private $track = true;

    /**
     * @param string $query
     * @return SearchCriteria
     */
    public function setQuery(string $query): SearchCriteria
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param int $size
     * @return SearchCriteria
     */
    public function setSize(int $size): SearchCriteria
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param int $page
     * @return SearchCriteria
     */
    public function setPage(int $page): SearchCriteria
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param bool $full
     * @return SearchCriteria
     */
    public function setFull(bool $full): SearchCriteria
    {
        $this->full = $full;
        return $this;
    }

    /**
     * @param bool $noCorrect
     * @return SearchCriteria
     */
    public function setNoCorrect(bool $noCorrect): SearchCriteria
    {
        $this->noCorrect = $noCorrect;
        return $this;
    }

    /**
     * @param array $facets
     * @return SearchCriteria
     */
    public function setFacets(array $facets): SearchCriteria
    {
        $this->facets = $facets;
        return $this;
    }

    /**
     * @param string $sort
     * @return SearchCriteria
     */
    public function setSort(string $sort): SearchCriteria
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @param bool $noTiming
     * @return SearchCriteria
     */
    public function setNoTiming(bool $noTiming): SearchCriteria
    {
        $this->noTiming = $noTiming;
        return $this;
    }

    /**
     * @param bool $track
     * @return SearchCriteria
     */
    public function setTrack(bool $track): SearchCriteria
    {
        $this->track = $track;
        return $this;
    }
}
