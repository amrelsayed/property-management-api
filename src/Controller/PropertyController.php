<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api')]
class PropertyController extends AbstractController
{
    use ValidationTrait;

    public function __construct(private PropertyRepository $propertyRepository, private ValidatorInterface $validator)
    {
    }

    #[Route('/properties', name: 'properties_create', methods: ['post'])]
    public function create(Request $request): JsonResponse
    {
        # validate
        $property = new Property;
        $data = $request->toArray();
        $this->fillPropertyObject($data, $property, true);

        $errors = $this->validator->validate($property);

        if (count($errors) > 0) {
            return $this->json(['errors' => $this->formatErrors($errors)], 400);
        }

        # add to db
        $this->propertyRepository->save($property);

        # return
        return $this->json([
            'message' => 'Property Created!',
        ]);
    }

    protected function fillPropertyObject(array $data, Property $property, bool $new = false): void
    {
        if ($new === true) {
            $property->setStatus('available');
            $property->setCreatedAt(new \DateTimeImmutable('now'));
        }

        $property->setTitle($data['title']);
        $property->setDescription($data['description']);
        $property->setLocation($data['location']);
        $property->setPrice($data['price']);
    }
}
