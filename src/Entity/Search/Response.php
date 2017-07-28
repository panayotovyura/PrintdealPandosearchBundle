<?php

namespace Printdeal\PandosearchBundle\Entity\Search;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Printdeal\PandosearchBundle\Criteria\SearchCriteria;
use Printdeal\PandosearchBundle\Entity\Timing;

class Response
{
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $total;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $hits;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $facets;

    /**
     * @var SearchCriteria
     * @Serializer\Type("Printdeal\PandosearchBundle\Criteria\SearchCriteria")
     */
    private $request;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $received;

    /**
     * @var Pagination
     * @Serializer\Type("Printdeal\PandosearchBundle\Entity\Search\Pagination")
     */
    private $pagination;

    /**
     * @var Timing
     * @Serializer\Type("Printdeal\PandosearchBundle\Entity\Timing")
     */
    private $timing;

    /**
     * @var ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion>
     * @Serializer\Type("ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion>")
     */
    private $suggestions;

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return array
     */
    public function getHits(): array
    {
        return $this->hits;
    }

    /**
     * @return array
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * @return SearchCriteria
     */
    public function getRequest(): SearchCriteria
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getReceived(): array
    {
        return $this->received;
    }

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * @return Timing
     */
    public function getTiming(): Timing
    {
        return $this->timing ?? (new Timing());
    }

    /**
     * @return ArrayCollection
     */
    public function getSuggestions(): ArrayCollection
    {
        return $this->suggestions ?? (new ArrayCollection());
    }
}
