<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Master;

use App\Domain\Master\Entity\MasterAccessLog;
use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class MasterAccessLogRepository implements MasterAccessLogRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(MasterAccessLog $accessLog): void
    {
        $this->em->persist($accessLog);
        $this->em->flush();
    }

    public function findById(int $id): ?MasterAccessLog
    {
        return $this->em->find(MasterAccessLog::class, $id);
    }

    public function findByToken(string $token): ?MasterAccessLog
    {
        return $this->em->getRepository(MasterAccessLog::class)->findOneBy(['token' => $token]);
    }

    public function findActiveByUserId(int $userId): array
    {
        return $this->em->getRepository(MasterAccessLog::class)->findBy([
            'userId' => (int)$userId,
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function findByUserId(int $userId): array
    {
        return $this->em->getRepository(MasterAccessLog::class)->findBy(
            ['userId' => (int)$userId],
            ['createdAt' => 'DESC']
        );
    }

    public function findAll(): array
    {
        return $this->em->getRepository(MasterAccessLog::class)->findBy(
            [],
            ['createdAt' => 'DESC']
        );
    }

    public function findAllActive(): array
    {
        return $this->em->getRepository(MasterAccessLog::class)->findBy([
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function terminateAllByUserId(int $userId): void
    {
        $this->em->createQueryBuilder()
            ->update(MasterAccessLog::class, 'm')
            ->set('m.isTerminated', ':terminated')
            ->set('m.updatedAt', ':now')
            ->where('m.userId = :userId')
            ->andWhere('m.isTerminated = :notTerminated')
            ->setParameter('terminated', true)
            ->setParameter('notTerminated', false)
            ->setParameter('userId', (int)$userId)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->execute();
    }
}
