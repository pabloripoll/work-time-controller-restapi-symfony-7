<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Admin;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdminRepository extends ServiceEntityRepository implements AdminRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    public function save(Admin $admin): void
    {
        $this->getEntityManager()->persist($admin);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Admin $admin): void
    {
        $this->getEntityManager()->remove($admin);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Admin
    {
        return $this->find($id);
    }

    public function findByUserId(int $userId): ?Admin
    {
        return $this->findOneBy(['user' => $userId]);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isActive = :active')
            ->andWhere('m.isBanned = :banned')
            ->setParameter('active', true)
            ->setParameter('banned', false)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllBanned(): array
    {
        return $this->findBy(['is_banned' => true], ['created_at' => 'DESC']);
    }

    public function findAllWithProfiles(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m', 'u', 'mp')
            ->join('m.user', 'u')
            ->leftJoin('App\Domain\Admin\Entity\AdminProfile', 'mp', 'WITH', 'mp.admin = m.id')
            ->orderBy('m.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByEmployeeId(int $employeeId): ?Admin
    {
        return $this->findOneBy(['employee' => $employeeId]);
    }

    public function findAllSuperadmins(): array
    {
        return $this->findBy(['is_superadmin' => true], ['created_at' => 'DESC']);
    }
}
