<?php

namespace App\Event;

use App\Entity\Property;

class PropertyStatusUpdatedEvent
{
    public function __construct(private Property $property)
    {
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}