<?php

namespace Printdeal\PandosearchBundle\Entity\Search;

use JMS\Serializer\Annotation as Serializer;

class Pagination
{
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $current;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("numPages")
     */
    private $pagesAmount;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("numResults")
     */
    private $resultsAmount;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("prelink")
     */
    private $previousLink;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("resultsPerPage")
     */
    private $resultsPerPage;

    /**
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @return int
     */
    public function getPagesAmount(): int
    {
        return $this->pagesAmount;
    }

    /**
     * @return int
     */
    public function getResultsAmount(): int
    {
        return $this->resultsAmount;
    }

    /**
     * @return string
     */
    public function getPreviousLink(): string
    {
        return $this->previousLink;
    }

    /**
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }
}
