<?php

namespace App\Service;

use App\Dto\ContractDto;
use App\Entity\ContractEntity;
use App\Mapper\ContractMapper;
use App\Repository\ContractRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContractService
{
    public function __construct(private ContractRepository $contractRepository) {}
    public function getContracts(int $page, int $limit): array
    {
        $paginator = $this->contractRepository->getContracts($page, $limit);

        $contracts = iterator_to_array($paginator);

        $contractsDto = array_map(
                fn(ContractEntity $contract)
                => ContractMapper::toContractDtoWithSpec($contract, $contract->getContractSpecs()),
            $contracts
        );

        return [
            'data' => $contractsDto,
            'total' => count($paginator),
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil(count($paginator) / $limit),
        ];
    }

    public function createContract(ContractDto $dto): ContractDto
    {
        $contract = ContractMapper::toContractEntity($dto);

        //Заполняем вычисляемые поля
        $contract->setId(null);

        $resultCreate = $this->contractRepository->createContract($contract);

        return ContractMapper::toContractDto($resultCreate);
    }

    public function getContract(string $id): ContractDto
    {
        $contract = $this->contractRepository->getContract($id);

        return ContractMapper::toContractDto($contract);
    }

    public function changeContract(ContractDto $dto): ContractDto
    {
        $contract = ContractMapper::toContractEntity($dto);

        $resultCreate = $this->contractRepository->changeContract($contract);

        return ContractMapper::toContractDto($resultCreate);
    }

    public function deleteContract(string $id): JsonResponse
    {
        $deletedRow = $this->contractRepository->deleteContract($id);

        if ($deletedRow > 0) {
            return new JsonResponse([
                'message' => "Договор успешно удален"
            ], JsonResponse::HTTP_OK);
        } else {
            return new JsonResponse([
                'message' => "Не найден договор с данным id"
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
