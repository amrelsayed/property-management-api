<?php

namespace App\DTO;

trait DTOTrait
{
    public static function fromArray($items): array
    {
        $data = [];

        foreach ($items as $item) {
            $dto = new self($item);
            $data[] = $dto;
        }

        return $data;
    }
}