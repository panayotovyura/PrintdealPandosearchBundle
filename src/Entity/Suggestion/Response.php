<?php

namespace Printdeal\PandosearchBundle\Entity\Suggestion;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Printdeal\PandosearchBundle\Entity\Timing;

class Response
{
    /**
     * @var ArrayCollection
     * @Serializer\Type("ArrayCollection")
     */
    private $hits;

    /**
     * @var ArrayCollection<Suggestion>
     * @Serializer\Type("ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion\Suggestion>")
     */
    private $suggestions;

    /**
     * @var Timing
     * @Serializer\Type("Printdeal\PandosearchBundle\Entity\Timing")
     */
    private $timing;

    /**
     * @return ArrayCollection
     */
    public function getHits(): ArrayCollection
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

    /**
     * @return Timing
     */
    public function getTiming(): Timing
    {
        return $this->timing;
    }
}
