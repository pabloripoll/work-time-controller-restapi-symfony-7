<?php

namespace App\Domain\Office\Repository;

use App\Domain\Office\Entity\Department;

interface DepartmentRepositoryInterface
{
    public function findById(int $id): ?Department;

    public function findAll(): array;

    public function findByName(string $name): ?Department;

    public function save(Department $country): void;

    public function delete(Department $country): void;
}
