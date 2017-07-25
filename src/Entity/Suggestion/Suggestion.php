<?php

namespace Printdeal\PandosearchBundle\Entity\Suggestion;

use JMS\Serializer\Annotation as Serializer;

class Suggestion
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
