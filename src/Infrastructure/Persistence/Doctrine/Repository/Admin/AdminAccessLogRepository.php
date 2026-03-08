<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Admin;

use App\Domain\Admin\Entity\AdminAccessLog;
use App\Domain\Admin\Repository\AdminAccessLogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AdminAccessLogRepository implements AdminAccessLogRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(AdminAccessLog $accessLog): void
    {
        $this->em->persist($accessLog);
        $this->em->flush();
    }

    public function findById(int $id): ?AdminAccessLog
    {
        return $this->em->find(AdminAccessLog::class, $id);
    }

    public function findByToken(string $token): ?AdminAccessLog
    {
        return $this->em->getRepository(AdminAccessLog::class)->findOneBy(['token' => $token]);
    }

    public function findActiveByUserId(int $userId): array
    {
        return $this->em->getRepository(AdminAccessLog::class)->findBy([
            'userId' => (int)$userId,
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function findByUserId(int $userId): array
    {
        return $this->em->getRepository(AdminAccessLog::class)->findBy(
            ['userId' => (int)$userId],
            ['createdAt' => 'DESC']
        );
    }

    public function findAll(): array
    {
        return $this->em->getRepository(AdminAccessLog::class)->findBy(
            [],
            ['createdAt' => 'DESC']
        );
    }

    public function findAllActive(): array
    {
        return $this->em->getRepository(AdminAccessLog::class)->findBy([
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function terminateAllByUserId(int $userId): void
    {
        $this->em->createQueryBuilder()
            ->update(AdminAccessLog::class, 'm')
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
