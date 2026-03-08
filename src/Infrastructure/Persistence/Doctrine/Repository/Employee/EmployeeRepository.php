<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employee;

use App\Domain\Employee\Entity\Employee;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Shared\ValueObject\Uuid;

class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
        $this->getEntityManager()->flush();
    }

    public function delete(Employee $employee): void
    {
        $this->getEntityManager()->remove($employee);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Employee
    {
        return $this->find($id);
    }

    public function findByUserId(int $userId): ?Employee
    {
        return $this->findOneBy(['user' => $userId]);
    }

    public function findByUuId(Uuid $Uuid): ?Employee
    {
        return $this->findOneBy(['employee' => $Uuid]);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isActive = :active')
            //->andWhere('m.isBanned = :banned')
            ->setParameter('active', true)
            //->setParameter('banned', false)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllBanned(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isBanned = :banned')
            ->setParameter('banned', true)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
