<?php

namespace App\Controller;

use Symfony\Component\Validator\ConstraintViolationList;

trait ValidationTrait
{
    protected function formatErrors(ConstraintViolationList $errors): array
    {
        $errorsMessages = [];

        foreach ($errors as $error) {
            $errorsMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorsMessages;
    }
}