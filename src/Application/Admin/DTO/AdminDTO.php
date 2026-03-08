<?php

declare(strict_types=1);

namespace App\Application\Admin\DTO;

readonly class AdminDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $email,
        public ?string $nickname,
        public ?string $avatar,
        public bool $isActive,
        public bool $isBanned,
        public bool $isSuperadmin,
        public ?int $employeeId,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt
    ) {
    }
}
