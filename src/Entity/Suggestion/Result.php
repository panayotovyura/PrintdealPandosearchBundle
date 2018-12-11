<?php

namespace Printdeal\PandosearchBundle\Entity\Suggestion;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

class Result
{
    /**
     * @var array
     */
    private $hits = [];

    /**
     * @var ArrayCollection<\Printdeal\PandosearchBundle\Entity\Suggestion>
     * @Serializer\Type("ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion>")
     */
    private $suggestions;

    /**
     * @return array
     */
    public function getHits(): array
    {
        return $this->hits;
    }

    /**
     * @return ArrayCollection
     */
    public function getSuggestions(): ArrayCollection
    {
        return $this->suggestions;
    }
}
