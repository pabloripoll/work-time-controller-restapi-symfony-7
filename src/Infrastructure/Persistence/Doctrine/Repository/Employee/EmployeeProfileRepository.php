<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Employee;

use App\Domain\Employee\Entity\EmployeeProfile;
use App\Domain\Employee\Repository\EmployeeProfileRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeProfileRepository extends ServiceEntityRepository implements EmployeeProfileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeProfile::class);
    }

    public function save(EmployeeProfile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(EmployeeProfile $profile): void
    {
        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?EmployeeProfile
    {
        return null;
    }

    public function findByEmployeeId(int $adminId): ?EmployeeProfile
    {
        return $this->findOneBy(['admin' => $adminId]);
    }

    public function findByFullName(string $name, string $surname): ?EmployeeProfile
    {
        return null;
    }

    /* public function findByUserId(int $userId): ?EmployeeProfile
    {
        return $this->em->getRepository(EmployeeProfile::class)
            ->createQueryBuilder('mp')
            ->where('mp.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    } */

    /* public function findByUuid(Uuid $uuid): ?EmployeeProfile
    {
        return $this->em->getRepository(EmployeeProfile::class)
            ->createQueryBuilder('mp')
            ->join('mp.user', 'u')
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', (string) $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    } */
}
