<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employment;

use App\Domain\Employment\Entity\EmploymentContract;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmploymentContractRepository extends ServiceEntityRepository implements EmploymentContractRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploymentContract::class);
    }

    public function save(EmploymentContract $contract): void
    {
        $this->getEntityManager()->persist($contract);
        $this->getEntityManager()->flush();
    }

    public function delete(EmploymentContract $contract): void
    {
        $this->getEntityManager()->remove($contract);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?EmploymentContract
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function findByEmployeeId(int $employeeId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.employee = :employeeId')
            ->setParameter('employeeId', $employeeId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByEmployeeId(int $employeeId): ?EmploymentContract
    {
        return $this->createQueryBuilder('c')
            ->where('c.employee = :employeeId')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('employeeId', $employeeId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
