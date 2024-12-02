<?php

namespace App\Event;

use App\Entity\Property;

class PropertyDeletedEvent
{
    public function __construct(private Property $property)
    {
    }

    public function getProperty(): Property
    {
        return $this->property;
    }
}