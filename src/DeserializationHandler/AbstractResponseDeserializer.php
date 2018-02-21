<?php

namespace Printdeal\PandosearchBundle\DeserializationHandler;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\JsonDeserializationVisitor;

abstract class AbstractResponseDeserializer
{
    /**
     * @var string
     */
    private $entity;

    /**
     * @var ArrayTransformerInterface
     */
    private $serializer;

    /**
     * ResponseDeserializer constructor.
     * @param string $entity
     * @param ArrayTransformerInterface $serializer
     */
    public function __construct(string $entity, ArrayTransformerInterface $serializer)
    {
        $this->entity = $entity;
        $this->serializer = $serializer;
    }

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param array $response
     * @return mixed
     * @SuppressWarnings(PHPMD)
     */
    public function deserializeResponse(JsonDeserializationVisitor $visitor, array $response)
    {
        return $this->serializer->fromArray($response, $this->entity);
    }
}
