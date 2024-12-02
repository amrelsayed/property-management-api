<?php

namespace App\Controller;

use App\DTO\PropertyDTO;
use App\Entity\Property;
use App\Event\PropertyCreatedEvent;
use App\Event\PropertyDeletedEvent;
use App\Event\PropertyStatusUpdatedEvent;
use App\Event\PropertyUpdatedEvent;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('api')]
class PropertyController extends AbstractController
{
    use ValidationTrait;

    public function __construct(
        private PropertyRepository $propertyRepository,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route('/properties', name: 'properties_list', methods: ['get'])]
    public function index(Request $request): JsonResponse
    {
        # set
        $limit = (int) $request->query->get('limit', 10);
        $page = (int) $request->query->get('page', 1);
        $status = $request->query->get('status');

        # get
        $properties = $this->propertyRepository->list($page, $limit, $status);

        $data = PropertyDTO::fromArray($properties);

        # return
        return $this->json([
            'message' => 'Propery list',
            'data' => $data,
            'meta' => [
                'currentPage' => $page,
                'perPage' => $limit,
                'totalItems' => count($properties),
                'totalPages' => ceil(count($properties) / $limit)
            ]
        ]);
    }

    #[Route('/properties', name: 'properties_create', methods: ['post'])]
    public function create(Request $request): JsonResponse
    {
        # prepare
        $property = new Property;
        $data = $request->toArray();
        $this->fillPropertyObject($data, $property, true);

        # validate
        $errors = $this->validator->validate($property);
        if (count($errors) > 0) {
            return $this->json(['errors' => $this->formatErrors($errors)], 400);
        }

        # save
        $this->propertyRepository->save($property);

        # log and notify
        $this->eventDispatcher->dispatch(new PropertyCreatedEvent($property));

        # return
        return $this->json([
            'message' => 'Property created!',
        ]);
    }

    #[Route('/properties/{id}', name: 'properties_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        # check
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            return $this->json(['error' => 'Property not found.'], 404);
        }

        # prepare
        $data = $request->toArray();
        $this->fillPropertyObject($data, $property);

        # validate
        $errors = $this->validator->validate($property);
        if (count(value: $errors) > 0) {
            return $this->json(['errors' => $this->formatErrors($errors)], 400);
        }

        # save
        $this->propertyRepository->save($property);

        # log and notify
        $this->eventDispatcher->dispatch(new PropertyUpdatedEvent($property));

        # return 
        return $this->json([
            'message' => 'Property updated!',
        ]);
    }

    #[Route('/properties/{id}', name: 'properties_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        # check
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            return $this->json(['error' => 'Property not found.'], 404);
        }

        # prepare
        $data = new PropertyDTO($property);

        # return
        return $this->json($data);
    }

    #[Route('/properties/{id}', name: 'properties_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        # check
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            return $this->json(['error' => 'Property not found.'], 404);
        }

        # remote
        $this->propertyRepository->remove($property);

        # log and notify
        $this->eventDispatcher->dispatch(new PropertyDeletedEvent($property));

        # return
        return $this->json([
            'message' => 'Property deleted!',
        ]);
    }

    #[Route('/properties/{id}/status', name: 'property_status_update', methods: ['PUT'])]
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        # check
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            return $this->json(['error' => 'Property not found.'], 404);
        }

        # validate
        $data = $request->toArray();

        if (!isset($data['status']) || !in_array($data['status'], Property::STATUS)) {
            return $this->json(['errors' => ['status' => 'should be one of the following values: ' . implode(', ', Property::STATUS)]], 400);
        }

        # save
        $property->setStatus($data['status']);
        $this->propertyRepository->save($property);

        # log and notify
        $this->eventDispatcher->dispatch(new PropertyStatusUpdatedEvent($property));

        # return 
        return $this->json([
            'message' => 'Property status updated!',
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
