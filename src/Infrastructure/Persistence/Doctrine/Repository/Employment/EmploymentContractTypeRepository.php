<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employment;

use App\Domain\Employment\Entity\EmploymentContractType;
use App\Domain\Employment\Repository\EmploymentContractTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmploymentContractTypeRepository extends ServiceEntityRepository implements EmploymentContractTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploymentContractType::class);
    }

    public function findById(int $id): ?EmploymentContractType
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['title' => 'ASC']);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('ct')
            ->where('ct.deletedAt IS NULL')
            ->orderBy('ct.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(EmploymentContractType $contractType): void
    {
        $this->getEntityManager()->persist($contractType);
        $this->getEntityManager()->flush();
    }

    public function delete(EmploymentContractType $contractType): void
    {
        $this->getEntityManager()->remove($contractType);
        $this->getEntityManager()->flush();
    }
}
