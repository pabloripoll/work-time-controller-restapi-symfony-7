<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Master;

use App\Domain\Master\Entity\MasterProfile;
use App\Domain\Master\Repository\MasterProfileRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MasterProfileRepository extends ServiceEntityRepository implements MasterProfileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MasterProfile::class);
    }

    public function save(MasterProfile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(MasterProfile $profile): void
    {
        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?MasterProfile
    {
        return $this->find($id);
    }

    public function findByMasterId(int $masterId): ?MasterProfile
    {
        return $this->findOneBy(['master' => $masterId]);
    }

    public function findByNickname(string $nickname): ?MasterProfile
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
