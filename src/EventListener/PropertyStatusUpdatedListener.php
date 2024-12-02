<?php

namespace App\EventListener;

use App\Event\PropertyStatusUpdatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PropertyStatusUpdatedListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: PropertyStatusUpdatedEvent::class)]
    public function onPropertyCreatedEvent($event): void
    {
        $this->logger->info('Property status updated', [
            'ID' => $event->getProperty()->getId(),
            'Title' => $event->getProperty()->getTitle(),
            'Status' => $event->getProperty()->getStatus(),
        ]);
    }
}
