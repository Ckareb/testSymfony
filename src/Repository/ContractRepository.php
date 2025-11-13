<?php

namespace App\Repository;


use App\Entity\ContractEntity;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


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
}
