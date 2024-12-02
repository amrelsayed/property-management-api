<?php

namespace App\EventListener;

use App\Event\PropertyCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PropertyCreatedListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: PropertyCreatedEvent::class)]
    public function onPropertyCreatedEvent($event): void
    {
        $this->logger->info('New property added', [
            'ID' => $event->getProperty()->getId(),
            'Title' => $event->getProperty()->getTitle()
        ]);
    }
}
