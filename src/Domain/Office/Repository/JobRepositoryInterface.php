<?php

namespace App\Domain\Office\Repository;

use App\Domain\Office\Entity\Job;

interface JobRepositoryInterface
{
    public function findById(int $id): ?Job;

    public function findAll(): array;

    public function findByDepartmentId(int $departmentId): array;

    public function save(Job $region): void;

    public function delete(Job $region): void;
}
