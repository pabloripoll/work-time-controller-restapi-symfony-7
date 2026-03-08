<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employment;

use App\Domain\Employment\Entity\EmploymentWorkday;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmploymentWorkdayRepository extends ServiceEntityRepository implements EmploymentWorkdayRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploymentWorkday::class);
    }

    public function save(EmploymentWorkday $workday): void
    {
        $this->getEntityManager()->persist($workday);
        $this->getEntityManager()->flush();
    }

    public function delete(EmploymentWorkday $workday): void
    {
        $this->getEntityManager()->remove($workday);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?EmploymentWorkday
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['startsDate' => 'DESC']);
    }

    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('w.startsDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByContractId(int $contractId): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.contract = :contractId')
            ->setParameter('contractId', $contractId)
            ->orderBy('w.startsDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByUserId(int $userId): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->andWhere('w.deletedAt IS NULL')
            ->setParameter('userId', $userId)
            ->orderBy('w.startsDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTodayByEmployeeId(int $userId): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->andWhere('w.deletedAt IS NULL')
            ->setParameter('userId', $userId)
            ->orderBy('w.startsDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
