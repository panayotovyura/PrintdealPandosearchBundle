<?php

namespace Printdeal\PandosearchBundle\Service;

use Printdeal\PandosearchBundle\Builder\BuilderInterface;
use Printdeal\PandosearchBundle\Criteria\SerializableInterface;
use Printdeal\PandosearchBundle\Exception\BuilderNotFoundException;

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
     * @throws BuilderNotFoundException
     */
    public function build(SerializableInterface $object): array
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($object)) {
                return $builder->build($object);
            }
        }

        throw new BuilderNotFoundException();
    }
}
