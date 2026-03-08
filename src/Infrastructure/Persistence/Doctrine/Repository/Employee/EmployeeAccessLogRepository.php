<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employee;

use App\Domain\Employee\Entity\EmployeeAccessLog;
use App\Domain\Employee\Repository\EmployeeAccessLogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeAccessLogRepository implements EmployeeAccessLogRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(EmployeeAccessLog $accessLog): void
    {
        $this->em->persist($accessLog);
        $this->em->flush();
    }

    public function findById(int $id): ?EmployeeAccessLog
    {
        return $this->em->find(EmployeeAccessLog::class, $id);
    }

    public function findByToken(string $token): ?EmployeeAccessLog
    {
        return $this->em->getRepository(EmployeeAccessLog::class)->findOneBy(['token' => $token]);
    }

    public function findActiveByUserId(int $userId): array
    {
        return $this->em->getRepository(EmployeeAccessLog::class)->findBy([
            'userId' => (int)$userId,
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function findByUserId(int $userId): array
    {
        return $this->em->getRepository(EmployeeAccessLog::class)->findBy(
            ['userId' => (int)$userId],
            ['createdAt' => 'DESC']
        );
    }

    public function findAll(): array
    {
        return $this->em->getRepository(EmployeeAccessLog::class)->findBy(
            [],
            ['createdAt' => 'DESC']
        );
    }

    public function findAllActive(): array
    {
        return $this->em->getRepository(EmployeeAccessLog::class)->findBy([
            'isTerminated' => false,
            'isExpired' => false,
        ], ['createdAt' => 'DESC']);
    }

    public function terminateAllByUserId(int $userId): void
    {
        $this->em->createQueryBuilder()
            ->update(EmployeeAccessLog::class, 'm')
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
