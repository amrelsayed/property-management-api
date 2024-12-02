<?php

namespace App\EventListener;

use App\Event\PropertyDeletedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PropertyDeletedListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: PropertyDeletedEvent::class)]
    public function onPropertyCreatedEvent($event): void
    {
        $this->logger->info('Property deleted', [
            'Property ID' => $event->getProperty()->getId(),
            'Porperty Title' => $event->getProperty()->getTitle()
        ]);
    }
}
