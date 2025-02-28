<?php

namespace App\EventListener;

use App\Event\PropertyUpdatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PropertyUpdatedListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: PropertyUpdatedEvent::class)]
    public function onPropertyCreatedEvent($event): void
    {
        $this->logger->info('Property updated', [
            'ID' => $event->getProperty()->getId(),
            'Title' => $event->getProperty()->getTitle()
        ]);
    }
}
