<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Master;

use App\Domain\Master\Entity\Master;
use App\Domain\Master\Repository\MasterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MasterRepository extends ServiceEntityRepository implements MasterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Master::class);
    }

    public function save(Master $admin): void
    {
        $this->getEntityManager()->persist($admin);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Master $admin): void
    {
        $this->getEntityManager()->remove($admin);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Master
    {
        return $this->find($id);
    }

    public function findByUserId(int $userId): ?Master
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
            ->leftJoin('App\Domain\Master\Entity\MasterProfile', 'mp', 'WITH', 'mp.master = m.id')
            ->orderBy('m.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
