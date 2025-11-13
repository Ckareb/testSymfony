<?php

namespace App\Controller;

use App\Dto\ContractDto;
use App\Service\ContractService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/contract')]
class ContractController extends AbstractController
{
    public function __construct(private ContractService $contractService) {}
    #[Route('/api/hello', name: 'api_hello', methods: ['GET'])]
    public function api_hello(): JsonResponse
    {
        return new JsonResponse(
            ["message" => "Hello"]
        );
    }

    #[Route('/pages', name: 'get_contracts', methods: ['POST'])]
    public function getContracts(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, (int) $request->query->get('limit', 10));
        $dtos = $this->contractService->getContracts($page, $limit);
        return $this->json($dtos);
    }

    #[Route('/create', name: 'create_contract', methods: ['POST'])]
    public function createContract(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Автоматическое создание DTO из JSON запроса
        $dto = $serializer->deserialize(
            $request->getContent(),
            ContractDto::class,
            'json'
        );

        $dto = $this->contractService->createContract($dto);
        return $this->json($dto);
    }

    #[Route('/{id}', name: 'get_contract', methods: ['GET'])]
    public function getContract(string $id): JsonResponse
    {
        $dto = $this->contractService->getContract($id);
        return $this->json($dto);
    }

    #[Route('/change', name: 'change_contract', methods: ['PUT'])]
    public function changeContract(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Автоматическое создание DTO из JSON запроса
        $dto = $serializer->deserialize(
            $request->getContent(),
            ContractDto::class,
            'json'
        );

        $dto = $this->contractService->changeContract($dto);
        return $this->json($dto);
    }

    #[Route('/{id}', name: 'delete_contract', methods: ['DELETE'])]
    public function deleteContract(string $id): JsonResponse
    {
        return $this->contractService->deleteContract($id);
    }
}
