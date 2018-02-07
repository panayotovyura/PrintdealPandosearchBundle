<?php

namespace Printdeal\PandosearchBundle\DeserializationHandler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Printdeal\PandosearchBundle\Entity\Search\Response;

class SearchResponseDeserializationHandler extends AbstractResponseDeserializer implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => Response::class,
                'method' => 'deserializeResponse',
            ]
        ];
    }
}
