<?php

namespace App\Repository;


use App\Entity\ContractEntity;


use Doctrine\DBAL\Connection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ContractRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractEntity::class);
        $this->em = $registry->getManager();
    }

    public function getContracts(int $page = 1, int $limit = 10): Paginator
    {
        $qb = $this->em->createQueryBuilder()
            ->select('c')
            ->from(ContractEntity::class, 'c')
            ->leftJoin('c.contractSpecs', 'cs')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }

    public function getContract(string $id): ?ContractEntity
    {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(ContractEntity::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createContract(ContractEntity $contract): ContractEntity
    {
        // Сохраняем в базу
        $this->em->persist($contract);
        $this->em->flush();

        return $contract;
    }

    public function changeContract(ContractEntity $contract): ContractEntity
    {
        $this->em->createQueryBuilder()
            ->update(ContractEntity::class, 'c')
            ->set('c.code', ' :code')
            ->set('c.name', ' :name')
            ->set('c.statusDate', ' :statusDate')
            ->set('c.price', ' :price')
            ->set('c.quantity', ' :quantity')
            ->where('c.id = :id')
            ->setParameter('id', $contract->getId())
            ->setParameter('code', $contract->getCode())
            ->setParameter('name', $contract->getName())
            ->setParameter('statusDate', $contract->getStatusDate())
            ->setParameter('price', $contract->getPrice())
            ->setParameter('quantity', $contract->getQuantity())
            ->getQuery()->execute();;


        return $this->getContract($contract->getId());
    }

    public function deleteContract(string $id): int
    {
        return $this->em->createQueryBuilder()
            ->delete(ContractEntity::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function uploadFileFromDb(string $id, UploadedFile $file): int
    {
        $binary = file_get_contents($file->getPathname());

        return $this->em->createQueryBuilder()
            ->update(ContractEntity::class, 'c')
            ->set('c.fileData', ':data')
            ->set('c.fileName', ':name')
            ->set('c.fileType', ':mime')
            ->where('c.id = :id')
            ->setParameter('data', $binary, \PDO::PARAM_LOB)
            ->setParameter('name', $file->getClientOriginalName())
            ->setParameter('mime', $file->getMimeType())
            ->setParameter('id', $id)
            ->getQuery()->execute();
    }

    public function getFileFromDb(int $id): ?array
    {
        $qb = $this->em->createQueryBuilder();

        $file = $qb->select('c.fileData', 'c.fileName', 'c.fileType')
            ->from(ContractEntity::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);

        if (!$file || !$file['fileData']) {
            return null;
        }

        $binary = is_resource($file['fileData'])
            ? stream_get_contents($file['fileData'])
            : $file['fileData'];

        return [
            'data' => $binary,
            'name' => $file['fileName'],
            'mime' => $file['fileType'],
        ];
    }

}
