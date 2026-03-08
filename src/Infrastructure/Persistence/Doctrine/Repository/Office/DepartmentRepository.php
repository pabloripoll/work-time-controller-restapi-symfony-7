<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Office;

use App\Domain\Office\Entity\Department;
use App\Domain\Office\Repository\DepartmentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DepartmentRepository extends ServiceEntityRepository implements DepartmentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $department): void
    {
        $this->getEntityManager()->persist($department);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Department $department): void
    {
        $this->getEntityManager()->remove($department);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Department
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }

    public function findByName(string $name): ?Department
    {
        return $this->findOneBy(['name' => $name]);
    }
}
