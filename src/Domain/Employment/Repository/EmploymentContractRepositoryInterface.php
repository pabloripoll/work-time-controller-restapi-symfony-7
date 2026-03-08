<?php

namespace App\Domain\Employment\Repository;

use App\Domain\Employment\Entity\EmploymentContract;

interface EmploymentContractRepositoryInterface
{
    public function save(EmploymentContract $contract): void;

    public function delete(EmploymentContract $contract): void;

    public function findById(int $id): ?EmploymentContract;

    public function findAll(): array;

    public function findByEmployeeId(int $employeeId): array;

    public function findActiveByEmployeeId(int $employeeId): ?EmploymentContract;
}
