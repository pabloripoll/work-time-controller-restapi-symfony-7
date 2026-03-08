<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Admin;

use App\Domain\Admin\Entity\AdminProfile;
use App\Domain\Admin\Repository\AdminProfileRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdminProfileRepository extends ServiceEntityRepository implements AdminProfileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminProfile::class);
    }

    public function save(AdminProfile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(AdminProfile $profile): void
    {
        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?AdminProfile
    {
        return $this->find($id);
    }

    public function findByAdminId(int $adminId): ?AdminProfile
    {
        return $this->findOneBy(['admin' => $adminId]);
    }

    public function findByNickname(string $nickname): ?AdminProfile
    {
        return $this->findOneBy(['nickname' => $nickname]);
    }

    public function existsByNickname(string $nickname): bool
    {
        $count = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.nickname = :nickname')
            ->setParameter('nickname', $nickname)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
