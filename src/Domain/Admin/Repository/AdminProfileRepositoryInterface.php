<?php

declare(strict_types=1);

namespace App\Domain\Admin\Repository;

use App\Domain\Admin\Entity\AdminProfile;

interface AdminProfileRepositoryInterface
{
    public function save(AdminProfile $profile): void;

    public function flush(): void;

    public function delete(AdminProfile $profile): void;

    public function findByAdminId(int $masterId): ?AdminProfile;

    public function existsByNickname(string $nickname): bool;
}
