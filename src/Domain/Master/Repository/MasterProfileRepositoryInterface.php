<?php

declare(strict_types=1);

namespace App\Domain\Master\Repository;

use App\Domain\Master\Entity\MasterProfile;

interface MasterProfileRepositoryInterface
{
    public function save(MasterProfile $profile): void;

    public function flush(): void;

    public function delete(MasterProfile $profile): void;

    public function findByMasterId(int $masterId): ?MasterProfile;

    public function existsByNickname(string $nickname): bool;
}
