<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\EmployeeContact;

interface EmployeeContactRepositoryInterface
{
    public function save(EmployeeContact $Contact): void;

    public function delete(EmployeeContact $Contact): void;

    public function flush(): void;

    public function findById(int $id): ?EmployeeContact;

    public function findByEmployeeId(int $employeeId): ?EmployeeContact;

    public function findByEmail(string $email): ?EmployeeContact;

    public function findByPhone(string $phone): array;
}
