<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Employment\Repository;

use App\Domain\Employment\Entity\EmploymentWorkdayClocking;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmploymentWorkdayClockingRepository extends ServiceEntityRepository implements EmploymentWorkdayClockingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploymentWorkdayClocking::class);
    }

    public function findById(int $id): ?EmploymentWorkdayClocking
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByWorkdayId(int $workdayId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.workday = :workdayId')
            ->setParameter('workdayId', $workdayId)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByUserId(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :userId')
            ->andWhere('c.deletedAt IS NULL')
            ->setParameter('userId', $userId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(EmploymentWorkdayClocking $clocking): void
    {
        $this->getEntityManager()->persist($clocking);
        $this->getEntityManager()->flush();
    }

    public function delete(EmploymentWorkdayClocking $clocking): void
    {
        $this->getEntityManager()->remove($clocking);
        $this->getEntityManager()->flush();
    }
}
