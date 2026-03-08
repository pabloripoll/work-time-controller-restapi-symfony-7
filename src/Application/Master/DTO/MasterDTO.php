<?php

declare(strict_types=1);

namespace App\Application\Master\DTO;

readonly class MasterDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $email,
        public ?string $nickname,
        public ?string $avatar,
        public bool $isActive,
        public bool $isBanned,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt
    ) {
    }
}
