<?php

namespace Tests\Printdeal\PandosearchBundle\Builder;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

abstract class AbstractBuilderTest extends TestCase
{
    /**
     * @var Serializer
     */
    protected static $serializer;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation', __DIR__ . '/../vendor/jms/serializer/src'
        );
        static::$serializer = SerializerBuilder::create()->build();
    }
}
