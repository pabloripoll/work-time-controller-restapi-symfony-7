<?php

namespace App\Domain\Admin\Repository;

use App\Domain\Admin\Entity\Admin;

interface AdminRepositoryInterface
{
    public function save(Admin $master): void;

    public function flush(): void;

    public function delete(Admin $master): void;

    public function findById(int $id): ?Admin;

    public function findByUserId(int $userId): ?Admin;

    public function findAll(): array;

    public function findAllActive(): array;

    public function findAllBanned(): array;

    public function findByEmployeeId(int $employeeId): ?Admin;
}
