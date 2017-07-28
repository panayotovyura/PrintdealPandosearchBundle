<?php

namespace Printdeal\PandosearchBundle\Entity\Suggestion;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Printdeal\PandosearchBundle\Entity\Timing;

class Response
{
    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $hits;

    /**
     * @var ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion>
     * @Serializer\Type("ArrayCollection<Printdeal\PandosearchBundle\Entity\Suggestion>")
     */
    private $suggestions;

    /**
     * @var Timing
     * @Serializer\Type("Printdeal\PandosearchBundle\Entity\Timing")
     */
    private $timing;

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
        return $this->suggestions ?? (new ArrayCollection());
    }

    /**
     * @return Timing
     */
    public function getTiming(): Timing
    {
        return $this->timing ?? (new Timing());
    }
}
