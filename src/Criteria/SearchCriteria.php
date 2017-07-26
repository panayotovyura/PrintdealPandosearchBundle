<?php

namespace Printdeal\PandosearchBundle\Criteria;

use JMS\Serializer\Annotation as Serializer;

final class SearchCriteria implements SerializableInterface
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
    private $full;

    /**
     * @var bool
     * @Serializer\SerializedName("nocorrect")
     * @Serializer\Type("boolean")
     */
    private $noCorrect;

    /**
     * @var array<string, string>
     * @Serializer\Type("array<string, string>")
     */
    private $facets = [];

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $sort = 'relevancy';

    /**
     * @var bool
     * @Serializer\SerializedName("notiming")
     * @Serializer\Type("boolean")
     */
    private $noTiming;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    private $track;

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

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->full;
    }

    /**
     * @return bool
     */
    public function isNoCorrect(): bool
    {
        return $this->noCorrect;
    }

    /**
     * @return array
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @return bool
     */
    public function isNoTiming(): bool
    {
        return $this->noTiming;
    }

    /**
     * @return bool
     */
    public function isTrack(): bool
    {
        return $this->track;
    }
}
