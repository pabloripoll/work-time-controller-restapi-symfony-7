<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\EmployeeWorkplace;

interface EmployeeWorkplaceRepositoryInterface
{
    public function save(EmployeeWorkplace $Workplace): void;

    public function delete(EmployeeWorkplace $Workplace): void;

    public function flush(): void;

    public function findById(int $id): ?EmployeeWorkplace;

    public function findByEmployeeId(int $employeeId): ?EmployeeWorkplace;

    public function findByDepartmentId(int $departmentId): array;

    public function findByJobId(int $jobId): array;

    public function findUnassigned(): array; // No department or job
}
