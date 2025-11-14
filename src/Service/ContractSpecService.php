<?php

namespace App\Service;

use App\Dto\ContractSpecDto;
use App\Exception\IllegalVariableException;
use App\Exception\NotFoundException;
use App\Mapper\ContractMapper;
use App\Repository\ContractSpecRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContractSpecService
{
    public function __construct(private contractSpecRepository $contractSpecRepository,
    private ContractService $contractService) {}
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

        $this->checkDto($dto);
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
        $this->checkDto($dto);
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

    private function checkDto(ContractSpecDto $dto): void
    {
        if($dto->getContractId() == null || $dto->getContractId() == "" || !ctype_digit($dto->getContractId())) {
            throw new IllegalVariableException('Договор не может иметь данное значение %s', $dto->getContractId());
        }

        if($dto->getCode() == null || $dto->getCode() == ""){
            throw new IllegalVariableException('Код не может иметь данное значение %s', $dto->getCode());
        }

        if($dto->getName() == null || $dto->getName() == ""){
            throw new IllegalVariableException('Имя не может иметь данное значение %s', $dto->getName());
        }

        if ($dto->getPrice() == null || $dto->getPrice() < 0 || !ctype_digit($dto->getPrice())) {
            throw new IllegalVariableException('Цена не может иметь данное значение %s', $dto->getPrice());
        }

        if ($dto->getQuantity() == null || $dto->getQuantity() < 0) {
            throw new IllegalVariableException('Количество не может иметь данное значение %s', $dto->getQuantity());
        }

        if ($dto->getCreateDate() < new \DateTimeImmutable()) {
            throw new IllegalVariableException('Дата должна быть больше %s', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));
        }

        if($this->contractService->getContract($dto->getContractId()) == null){
            throw new NotFoundException('Данный договор не найден %s', $dto->getContractId());
        }
    }
}
