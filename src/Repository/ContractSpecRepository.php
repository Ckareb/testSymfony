<?php

namespace App\Repository;


use App\Dto\ContractSpecDto;
use App\Entity\ContractEntity;

use App\Entity\ContractSpecEntity;
use Doctrine\DBAL\Connection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ContractSpecRepository extends ServiceEntityRepository
{
    private $em;

    private Connection $conn;

    public function __construct(ManagerRegistry $registry, Connection $conn)
    {
        parent::__construct($registry, ContractEntity::class);
        $this->em = $registry->getManager();
        $this->conn = $conn;
    }

    public function getContractSpecs(int $page = 1, int $limit = 10): ?array
    {
        $offset = ($page - 1) * $limit;

        $sql = "
                SELECT
        cs.id              AS spec_id,
        cs.contract_id     AS spec_contract_id,
        cs.code            AS spec_code,
        cs.name            AS spec_name,
        cs.create_date     AS spec_create_date,
        cs.price           AS spec_price,
        cs.quantity        AS spec_quantity,

        c.id               AS contract_id,
        c.code             AS contract_code,
        c.name             AS contract_name,
        c.status_date      AS contract_status_date,
        c.price           AS contract_price,
        c.quantity        AS contract_quantity
            FROM edu_php.t_contract_spec cs
            LEFT JOIN edu_php.t_contract c
                ON cs.contract_id = c.id
            ORDER BY cs.code ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative(); // массив всех строк
    }
    public function getContractSpec(string $id): ?array
    {
        $sql = "SELECT * FROM edu_php.t_contract_spec WHERE id = :id";

        // Подготавливаем и выполняем запрос
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $result = $stmt->executeQuery(); // для SELECT

        // Получаем первую строку как массив
        $data = $result->fetchAssociative();

        return $data;
    }

    public function createContractSpec(ContractSpecDto $contractSpec): ?array
    {
        $sql = '
            INSERT INTO edu_php.t_contract_spec (contract_id, code, name, create_date, price, quantity)
            VALUES (:contract_id, :code, :name, :create_date, :price, :quantity)
            RETURNING *
        ';

        // безопасная подстановка параметров (аналог preparedStatement)
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('contract_id', $contractSpec->getContractId());
        $stmt->bindValue('code', $contractSpec->getCode());
        $stmt->bindValue('name', $contractSpec->getName());
        $stmt->bindValue('quantity', $contractSpec->getQuantity());
        $stmt->bindValue('price', $contractSpec->getPrice());
        $stmt->bindValue('create_date', $contractSpec->getCreateDate()->format('Y-m-d H:i:s'));

        // выполняем запрос и получаем id новой записи
        $result = $stmt->executeQuery();
        return $result->fetchAssociative();
    }

    public function changeContractSpec(ContractSpecDto $contractSpec): ?array
    {
        $sql = '
            UPDATE edu_php.t_contract_spec
            SET code = :code, name = :name, create_date = :create_date, price = :price, quantity = :quantity
            WHERE id = :id
            RETURNING *
        ';

        // безопасная подстановка параметров (аналог preparedStatement)
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('id', $contractSpec->getId());
        //$stmt->bindValue('contract_id', $contractSpec->getContractId());
        $stmt->bindValue('code', $contractSpec->getCode());
        $stmt->bindValue('name', $contractSpec->getName());
        $stmt->bindValue('quantity', $contractSpec->getQuantity());
        $stmt->bindValue('price', $contractSpec->getPrice());
        $stmt->bindValue('create_date', $contractSpec->getCreateDate()->format('Y-m-d H:i:s'));

        // выполняем запрос и получаем id новой записи
        $result = $stmt->executeQuery();
        return $result->fetchAssociative();
    }

    public function deleteContractSpec(string $id): int
    {
        $sql = "DELETE FROM edu_php.t_contract_spec WHERE id = :id";

        return $this->conn->executeStatement($sql, ['id' => $id]);
    }

    public function uploadFilePathFromDb(string $id, string $filePath, string $fileName, string $fileType): int
    {

        return $this->em->createQueryBuilder()
            ->update(ContractSpecEntity::class, 'c')
            ->set('c.filePath', ':path')
            ->set('c.fileName', ':name')
            ->set('c.fileType', ':mime')
            ->where('c.id = :id')
            ->setParameter('path', $filePath)
            ->setParameter('name', $fileName)
            ->setParameter('mime', $fileType)
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function getFilePathFromDb(int $id): ?array
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('c.filePath', 'c.fileName', 'c.fileType')
            ->from(ContractSpecEntity::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
