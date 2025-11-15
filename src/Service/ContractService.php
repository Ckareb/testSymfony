<?php

namespace App\Service;

use App\Dto\ContractDto;
use App\Entity\ContractEntity;
use App\Exception\IllegalVariableException;
use App\Exception\NotFoundException;
use App\Mapper\ContractMapper;
use App\Repository\ContractRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        $this->checkDto($dto);
        $contract = ContractMapper::toContractEntity($dto);

        //Заполняем вычисляемые поля
        $contract->setId(null);
        $dto->setStatusDate(new \DateTimeImmutable());

        $resultCreate = $this->contractRepository->createContract($contract);

        return ContractMapper::toContractDto($resultCreate);
    }

    public function getContract(string $id): ?ContractDto
    {
        $contract = $this->contractRepository->getContract($id);

        return ContractMapper::toContractDto($contract);
    }

    public function changeContract(ContractDto $dto): ContractDto
    {
        $this->checkDto($dto);
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

    public function uploadFileFromDb(string $id, UploadedFile $file): JsonResponse
    {
        $result = $this->contractRepository->uploadFileFromDb($id, $file);

        if ($result != null) {
            return new JsonResponse([
                'message' => "Файл успешно сохранен"
            ], JsonResponse::HTTP_OK);
        } else {
            return new JsonResponse([
                'message' => "Файл не предоставлен"
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function getFileFromDb(string $id): array
    {
        $result = $this->contractRepository->getFileFromDb($id);

        if ($result == null) {
            throw new IllegalVariableException('Файл не найден',null);
        }

        return $result;
    }



    private function checkDto(ContractDto $dto): void{

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

        if ($dto->getStatusDate() < new \DateTimeImmutable()) {
            throw new IllegalVariableException('Дата должна быть больше %s', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));
        }
    }
}
