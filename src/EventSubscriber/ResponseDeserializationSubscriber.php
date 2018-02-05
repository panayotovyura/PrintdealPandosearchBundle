<?php

namespace Printdeal\PandosearchBundle\EventSubscriber;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Printdeal\PandosearchBundle\Converter\HitsConverterInterface;
use Printdeal\PandosearchBundle\Entity;

class ResponseDeserializationSubscriber implements EventSubscriberInterface
{
    /**
     * @var HitsConverterInterface[]
     */
    private $hitsConverters = [];

    /**
     * @var string
     */
    private $searchConverter;

    /**
     * @var string
     */
    private $suggestionConverter;

    /**
     * SearchResponseDeserializationHandler constructor.
     * @param string $searchConverter
     * @param string $suggestionConverter
     */
    public function __construct(
        string $searchConverter = '',
        string $suggestionConverter = ''
    ) {
        $this->searchConverter = $searchConverter;
        $this->suggestionConverter = $suggestionConverter;
    }

    /**
     * @param string $name
     * @param HitsConverterInterface $hitsConverter
     */
    public function registerConverter(
        string $name,
        HitsConverterInterface $hitsConverter
    ) {
        $this->hitsConverters[$name] = $hitsConverter;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_DESERIALIZE,
                'method' => 'postDeserializeSearchResponse',
                'class' => Entity\Search\Response::class,
                'format' => 'json',
            ],
            [
                'event' => Events::POST_DESERIALIZE,
                'method' => 'postDeserializeSuggestionResponse',
                'class' => Entity\Suggestion\Response::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function postDeserializeSearchResponse(ObjectEvent $event)
    {
        if (!$this->doesConverterExist($this->searchConverter)) {
            return;
        }

        $response = $event->getObject();
        if (!$response instanceof Entity\Search\Response) {
            return;
        }
        $hits = $this->hitsConverters[$this->searchConverter]->convert($response->getHits());
        $response->setHits($hits);
    }

    /**
     * @param ObjectEvent $event
     */
    public function postDeserializeSuggestionResponse(ObjectEvent $event)
    {
        if (!$this->doesConverterExist($this->suggestionConverter)) {
            return;
        }

        $response = $event->getObject();
        if (!$response instanceof Entity\Suggestion\Response) {
            return;
        }
        $hits = $this->hitsConverters[$this->suggestionConverter]->convert($response->getHits());
        $response->setHits($hits);
    }

    /**
     * @param string $converter
     * @return bool
     */
    private function doesConverterExist(string $converter): bool
    {
        if (!isset($this->hitsConverters[$converter])) {
            return false;
        }
        return true;
    }
}
