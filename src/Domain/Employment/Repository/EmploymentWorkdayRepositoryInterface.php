<?php

namespace App\Domain\Employment\Repository;

use App\Domain\Employment\Entity\EmploymentWorkday;

interface EmploymentWorkdayRepositoryInterface
{
    public function save(EmploymentWorkday $workday): void;

    public function delete(EmploymentWorkday $workday): void;

    public function findById(int $id): ?EmploymentWorkday;

    public function findAll(): array;

    public function findByUserId(int $userId): array;

    public function findByContractId(int $contractId): array;

    public function findActiveByUserId(int $userId): array;

    public function findTodayByEmployeeId(int $userId): array;
}
