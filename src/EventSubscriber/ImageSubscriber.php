<?php

namespace App\EventSubscriber;

use App\Event\ImageUploadedEvent;
use App\Message\ImageThumb;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImageSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $bus)
    {}

    public static function getSubscribedEvents()
    {
        return [
            ImageUploadedEvent::NAME => 'onImageUpload',
        ];
    }

    public function onImageUpload(ImageUploadedEvent $event)
    {
        $id = $event->getImage()->getId();
        $this->bus->dispatch(new ImageThumb($id));
    }
}
