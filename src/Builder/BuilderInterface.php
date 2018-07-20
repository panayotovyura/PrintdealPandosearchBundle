<?php

namespace Printdeal\PandosearchBundle\Builder;

use Printdeal\PandosearchBundle\Criteria\SerializableInterface;

interface BuilderInterface
{
    /**
     * @param SerializableInterface $criteria
     * @return array
     */
    public function build(SerializableInterface $criteria): array;

    /**
     * @param SerializableInterface $class
     * @return bool
     */
    public function supports(SerializableInterface $class): bool;
}
