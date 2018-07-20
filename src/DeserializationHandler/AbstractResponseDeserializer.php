<?php

namespace Printdeal\PandosearchBundle\DeserializationHandler;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\JsonDeserializationVisitor;

abstract class AbstractResponseDeserializer
{
    const DEFAULT_METHOD = 'deserializeResponse';
    const DEFAULT_FORMAT = 'json';

    /**
     * @var string
     */
    private $entity;

    /**
     * @var ArrayTransformerInterface
     */
    private $arrayTransformer;

    /**
     * AbstractResponseDeserializer constructor.
     * @param string $entity
     * @param ArrayTransformerInterface $arrayTransformer
     */
    public function __construct(string $entity, ArrayTransformerInterface $arrayTransformer)
    {
        $this->entity = $entity;
        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param array $response
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function deserializeResponse(JsonDeserializationVisitor $visitor, array $response)
    {
        return $this->arrayTransformer->fromArray($response, $this->entity);
    }
}
