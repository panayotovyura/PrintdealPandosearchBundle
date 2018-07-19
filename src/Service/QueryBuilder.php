<?php

namespace Printdeal\PandosearchBundle\Service;

use Printdeal\PandosearchBundle\Builder\BuilderInterface;
use Printdeal\PandosearchBundle\Criteria\SerializableInterface;

class QueryBuilder
{
    /**
     * @var BuilderInterface[]
     */
    private $builders = [];

    /**
     * @param BuilderInterface $builder
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    /**
     * @param SerializableInterface $object
     * @return array
     * @throws \Exception
     */
    public function build(SerializableInterface $object): array
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($object)) {
                return $builder->build($object);
            }
        }

        //todo: add proper exception;
        throw new \Exception();
    }
}
