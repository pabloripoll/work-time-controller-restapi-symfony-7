<?php

declare(strict_types=1);

namespace App\Domain\Master\Repository;

use App\Domain\Master\Entity\MasterAccessLog;

interface MasterAccessLogRepositoryInterface
{
    public function save(MasterAccessLog $accessLog): void;

    public function findById(int $id): ?MasterAccessLog;

    public function findByToken(string $token): ?MasterAccessLog;

    /**
     * @return MasterAccessLog[]
     */
    public function findActiveByUserId(int $userId): array;

    /**
     * @return MasterAccessLog[]
     */
    public function findByUserId(int $userId): array;

    /**
     * @return MasterAccessLog[]
     */
    public function findAll(): array;

    /**
     * @return MasterAccessLog[]
     */
    public function findAllActive(): array;

    public function terminateAllByUserId(int $userId): void;
}
