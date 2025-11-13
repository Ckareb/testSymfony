<?php

namespace App\Mapper;

use App\Dto\ContractDto;
use App\Dto\ContractDtoWithSpec;
use App\Dto\ContractSpecDto;
use App\Dto\ContractSpecDtoWithContr;
use App\Entity\ContractEntity;
use App\Entity\ContractSpecEntity;
use Doctrine\Common\Collections\Collection;

class ContractMapper
{
    public static function toContractDto(ContractEntity $contractEntity): ContractDto{
        $dto = new ContractDto();
        $dto->setId($contractEntity->getId());
        $dto->setCode($contractEntity->getCode());
        $dto->setName($contractEntity->getName());
        $dto->setStatusDate($contractEntity->getStatusDate());
        $dto->setPrice($contractEntity->getPrice());
        $dto->setQuantity($contractEntity->getQuantity());
        return $dto;
    }

    public static function toContractEntity(ContractDto $contractDto): ContractEntity{
        $entity = new ContractEntity();
        $entity->setId($contractDto->getId());
        $entity->setCode($contractDto->getCode());
//        $entity->setCreateUser($contractDto->createUser);
//        $entity->setLastUpdateUser($contractDto->lastUpdateUser);
//        $entity->setCreateDate($contractDto->createDate);
//        $entity->setLastUpdateDate($contractDto->lastUpdateDate);
//        $entity->setContractNumber($contractDto->contractNumber);
//        $entity->setContractDate($contractDto->contractDate);
//        $entity->setStatusId($contractDto->statusId);
        $entity->setName($contractDto->getName());
        $entity->setStatusDate($contractDto->getStatusDate());
        $entity->setPrice($contractDto->getPrice());
        $entity->setQuantity($contractDto->getQuantity());
//        $entity->setSupContrId($contractDto->supContrId);
//        $entity->setSupContragId($contractDto->supContragId);
//        $entity->setValidStartDate($contractDto->validStartDate);
//        $entity->setValidEndDate($contractDto->validEndDate);
//        $entity->setContrId($contractDto->contrId);
//        $entity->setContrUnitId($contractDto->contrUnitId);
//        $entity->setContractagId($contractDto->contragId);
//        $entity->setCurrency($contractDto->currency);
//        $entity->setNotes($contractDto->notes);
//        $entity->setWithVat($contractDto->withVat);
//        $entity->setClassId($contractDto->classId);
        return $entity;
    }

    public static function toContractDtoWithSpec(ContractEntity $contractEntity,Collection  $contractSpecEntities): ContractDto{
        $dto = new ContractDtoWithSpec();
        $dto->setId($contractEntity->getId());
        $dto->setCode($contractEntity->getCode());
        $dto->setName($contractEntity->getName());
        $dto->setStatusDate($contractEntity->getStatusDate());
        $dto->setPrice($contractEntity->getPrice());
        $dto->setQuantity($contractEntity->getQuantity());
        $dto->setContractSpec(
                array_map(function (ContractSpecEntity $contractSpecEntity) {
                    $contractSpecDto = new ContractDtoWithSpec();
                    $contractSpecDto->setId($contractSpecEntity->getId());
                    $contractSpecDto->setCode($contractSpecEntity->getCode());
                    $contractSpecDto->setName($contractSpecEntity->getName());
                    $contractSpecDto->setStatusDate($contractSpecEntity->getCreateDate());
                    $contractSpecDto->setPrice($contractSpecEntity->getPrice());
                    $contractSpecDto->setQuantity($contractSpecEntity->getQuantity());
                return $contractSpecDto;
        }, $contractSpecEntities->toArray())
        );

        return $dto;
    }

    public static function toContractSpecDtoGetSQL(array $contractSpec): ContractSpecDto{
        $dto = new ContractSpecDto();
        $dto->setId($contractSpec['id']);
        $dto->setContractId($contractSpec["contract_id"]);
        $dto->setCode($contractSpec["code"]);
        $dto->setName($contractSpec["name"]);
        $dto->setCreateDate(
            isset($contractSpec['create_date'])
                ? new \DateTimeImmutable($contractSpec['create_date'])
                : null
        );
        $dto->setPrice($contractSpec["price"]);
        $dto->setQuantity($contractSpec["quantity"]);
        return $dto;
    }

    public static function toInsertArray(ContractSpecDto $dto): array
    {
        return [
            'contract_id' => $dto->getContractId(),
            'name' => $dto->getName(),
            'code' => $dto->getCode(),
            'created_date' => $dto->getCreateDate()?->format('Y-m-d H:i:s'),
            'price' => $dto->getPrice(),
            'quantity' => $dto->getQuantity(),
        ];
    }

    public static function toContractSpecDtoGetSQLWithContr(array $contractSpec): ContractSpecDtoWithContr{
        $dto = new ContractSpecDtoWithContr();


        $dto->setId($contractSpec['spec_id']);
        $dto->setContractId($contractSpec['spec_contract_id']);
        $dto->setCode($contractSpec['spec_code']);
        $dto->setName($contractSpec['spec_name']);
        $dto->setCreateDate(
            isset($contractSpec['spec_create_date'])
                ? new \DateTimeImmutable($contractSpec['spec_create_date'])
                : null
        );
        $dto->setPrice($contractSpec['spec_price']);
        $dto->setQuantity($contractSpec['spec_quantity']);


        $contractDto = new ContractDto();
        $contractDto->setId($contractSpec['contract_id']);
        $contractDto->setCode($contractSpec['contract_code'] ?? '');
        $contractDto->setName($contractSpec['contract_name'] ?? '');
        $contractDto->setPrice($contractSpec['contract_price'] ?? '');
        $contractDto->setQuantity($contractSpec['contract_quantity'] ?? '');


        $contractDto->setStatusDate(
            isset($contractSpec['spec_create_date'])
            ?new \DateTimeImmutable($contractSpec['contract_status_date'])
            : null
        );


        $dto->setContract($contractDto);

        return $dto;
    }
}
