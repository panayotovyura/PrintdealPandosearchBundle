<?php

namespace Printdeal\PandosearchBundle\Entity\Suggestion;

class DidYouMean
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $text;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $highlighted;

    /**
     * @var bool
     */
    private $assumed;

    /**
     * @var Result
     * @Serializer\Type("Printdeal\PandosearchBundle\Entity\Suggestion\Result")
     */
    private $result;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHighlighted(): string
    {
        return $this->highlighted;
    }

    /**
     * @return bool
     */
    public function isAssumed(): bool
    {
        return $this->assumed;
    }

    /**
     * @return Result
     */
    public function getResult(): Result
    {
        return $this->result;
    }
}
