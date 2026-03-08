<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\EmployeeAccessLog;

interface EmployeeAccessLogRepositoryInterface
{
    public function save(EmployeeAccessLog $accessLog): void;

    public function findById(int $id): ?EmployeeAccessLog;

    public function findByToken(string $token): ?EmployeeAccessLog;

    /**
     * @return EmployeeAccessLog[]
     */
    public function findActiveByUserId(int $userId): array;

    /**
     * @return EmployeeAccessLog[]
     */
    public function findByUserId(int $userId): array;

    /**
     * @return EmployeeAccessLog[]
     */
    public function findAll(): array;

    /**
     * @return EmployeeAccessLog[]
     */
    public function findAllActive(): array;

    public function terminateAllByUserId(int $userId): void;
}
