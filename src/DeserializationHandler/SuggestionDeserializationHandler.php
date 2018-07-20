<?php

namespace Printdeal\PandosearchBundle\DeserializationHandler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Printdeal\PandosearchBundle\Entity\Suggestion\Response;

class SuggestionDeserializationHandler extends AbstractResponseDeserializer implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => self::DEFAULT_FORMAT,
                'type' => Response::class,
                'method' => self::DEFAULT_METHOD,
            ]
        ];
    }
}
