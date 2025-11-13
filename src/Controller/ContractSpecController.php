<?php

namespace App\Controller;

use App\Dto\ContractSpecDto;
use App\Service\ContractSpecService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/contract/spec')]
class ContractSpecController extends AbstractController
{
    public function __construct(private ContractSpecService $contractSpecService) {}

    #[Route('/pages', name: 'get_contract_specs', methods: ['POST'])]
    public function getContractSpecs(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, (int) $request->query->get('limit', 10));
        $dtos = $this->contractSpecService->getContractSpecs($page, $limit);
        return $this->json($dtos);
    }

    #[Route('/create', name: 'create_contract_spec', methods: ['POST'])]
    public function createContractSpec(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Автоматическое создание DTO из JSON запроса
        $dto = $serializer->deserialize(
            $request->getContent(),
            ContractSpecDto::class,
            'json'
        );

        $dto = $this->contractSpecService->createContractSpec($dto);
        return $this->json($dto);
    }

    #[Route('/{id}', name: 'get_contract_spec', methods: ['GET'])]
    public function getContractSpec(string $id): JsonResponse
    {
        $dto = $this->contractSpecService->getContractSpec($id);
        return $this->json($dto);
    }

    #[Route('/change', name: 'change_contract_spec', methods: ['PUT'])]
    public function changeContractSpec(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Автоматическое создание DTO из JSON запроса
        $dto = $serializer->deserialize(
            $request->getContent(),
            ContractSpecDto::class,
            'json'
        );

        $dto = $this->contractSpecService->changeContractSpec($dto);
        return $this->json($dto);
    }

    #[Route('/{id}', name: 'delete_contract', methods: ['DELETE'])]
    public function deleteContractSpec(string $id): JsonResponse
    {
        return $this->contractSpecService->deleteContractSpec($id);
    }
}
