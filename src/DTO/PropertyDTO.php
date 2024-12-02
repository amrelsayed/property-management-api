<?php

namespace App\DTO;

use App\Entity\Property;

class PropertyDTO
{
    public int $id;

    public string $title;

    public string $description;

    public string $price;

    public string $status;

    public string $location;

    public \DateTimeImmutable $createdAt;

    public function __construct(Property $property)
    {
        $this->id = $property->getId();
        $this->title = $property->getTitle();
        $this->description = $property->getDescription();
        $this->price = $property->getPrice();
        $this->location = $property->getLocation();
        $this->createdAt = $property->getCreatedAt();
    }
}