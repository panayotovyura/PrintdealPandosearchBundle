<?php

namespace Printdeal\PandosearchBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

class Suggestion
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $text;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("highlighted")
     */
    private $html;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }
}
