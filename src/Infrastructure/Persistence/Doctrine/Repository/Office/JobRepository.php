<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Office;

use App\Domain\Office\Entity\Job;
use App\Domain\Office\Repository\JobRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class JobRepository extends ServiceEntityRepository implements JobRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function save(Job $region): void
    {
        $this->getEntityManager()->persist($region);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Job $region): void
    {
        $this->getEntityManager()->remove($region);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Job
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['title' => 'ASC']);
    }

    public function findByDepartmentId(int $departmentId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.department = :departmentId')
            ->setParameter('departmentId', $departmentId)
            ->orderBy('r.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
