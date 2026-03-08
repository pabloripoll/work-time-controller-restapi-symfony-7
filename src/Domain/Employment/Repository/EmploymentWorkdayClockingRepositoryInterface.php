<?php

namespace App\Domain\Employment\Repository;

use App\Domain\Employment\Entity\EmploymentWorkdayClocking;

interface EmploymentWorkdayClockingRepositoryInterface
{
    public function save(EmploymentWorkdayClocking $clocking): void;

    public function delete(EmploymentWorkdayClocking $clocking): void;

    public function findById(int $id): ?EmploymentWorkdayClocking;

    public function findAll(): array;

    public function findByUserId(int $userId): array;

    public function findByWorkdayId(int $workdayId): array;

    public function findActiveByUserId(int $userId): array;
}
