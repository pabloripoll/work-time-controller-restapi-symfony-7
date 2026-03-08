<?php

namespace App\Domain\Master\Repository;

use App\Domain\Master\Entity\Master;

interface MasterRepositoryInterface
{
    public function save(Master $master): void;

    public function flush(): void;

    public function findById(int $id): ?Master;

    public function findByUserId(int $userId): ?Master;

    public function findAll(): array;

    public function findAllActive(): array;

    public function findAllBanned(): array;

    public function delete(Master $master): void;
}
