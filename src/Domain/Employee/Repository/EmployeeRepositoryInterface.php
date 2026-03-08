<?php

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\Employee;
use App\Domain\Shared\ValueObject\Uuid;

interface EmployeeRepositoryInterface
{
    public function save(Employee $employee): void;

    public function delete(Employee $employee): void;

    public function flush(): void;

    public function findById(int $id): ?Employee;

    public function findByUserId(int $userId): ?Employee;

    public function findByUuid(Uuid $Uuid): ?Employee;

    public function findAll(): array;

    public function findAllActive(): array;

    public function findAllBanned(): array;
}
