<?php

namespace App\Service;

use App\Dto\ContractSpecDto;
use App\Mapper\ContractMapper;
use App\Repository\ContractSpecRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContractSpecService
{
    public function __construct(private contractSpecRepository $contractSpecRepository) {}
    public function getContractSpecs(int $page, int $limit): ?array
    {
        $contracts = $this->contractSpecRepository->getContractSpecs($page, $limit);

        $contractsSpecDto = array_map(
                fn(?array $contract)
                => ContractMapper::toContractSpecDtoGetSQLWithContr($contract),
            $contracts
        );

        return [
            'data' => $contractsSpecDto,
            'total' => count($contracts),
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil(count($contracts) / $limit),
        ];
    }

    public function createContractSpec(ContractSpecDto $dto): ContractSpecDto
    {

        //Заполняем вычисляемые поля
        $dto->setId(null);

        //$contractSpec = ContractMapper::toInsertArray($dto);

        $resultCreate = $this->contractSpecRepository->createContractSpec($dto);

        return ContractMapper::toContractSpecDtoGetSQL($resultCreate);
    }

    public function getContractSpec(string $id): ContractSpecDto
    {
        $contractSpec = $this->contractSpecRepository->getContractSpec($id);

        return ContractMapper::toContractSpecDtoGetSQL($contractSpec);
    }

    public function changeContractSpec(ContractSpecDto $dto): ContractSpecDto
    {
        $resultCreate = $this->contractSpecRepository->changeContractSpec($dto);

        return ContractMapper::toContractSpecDtoGetSQL($resultCreate);
    }

    public function deleteContractSpec(string $id): JsonResponse
    {
        $deletedRow = $this->contractSpecRepository->deleteContractSpec($id);

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
