<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\EmployeeProfile;

interface EmployeeProfileRepositoryInterface
{
    public function save(EmployeeProfile $profile): void;

    public function delete(EmployeeProfile $profile): void;

    public function flush(): void;

    public function findById(int $id): ?EmployeeProfile;

    public function findByEmployeeId(int $employeeId): ?EmployeeProfile;

    public function findByFullName(string $name, string $surname): ?EmployeeProfile;
}
