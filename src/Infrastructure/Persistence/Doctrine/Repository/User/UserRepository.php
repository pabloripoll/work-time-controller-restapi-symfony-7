<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\ValueObject\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?User
    {
        return $this->find($id);
    }

    public function findByUuid(Uuid $uuid): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', (string) $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByEmailAndRole(Email $email, UserRole $role): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->andWhere('u.role = :role')
            ->setParameter('email', (string) $email)
            ->setParameter('role', (string) $role)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function existsByEmail(Email $email): bool
    {
        $count = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.email = :email')
            ->setParameter('email', (string) $email)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    public function findByRolePaginated(UserRole $role, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $users = $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('role',(string)  $role)
            ->orderBy('u.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $total = $this->countByRole($role);

        return [
            'data' => $users,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ];
    }

    public function countByRole(UserRole $role): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.role = :role')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('role', (string) $role)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.deletedAt IS NULL')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
