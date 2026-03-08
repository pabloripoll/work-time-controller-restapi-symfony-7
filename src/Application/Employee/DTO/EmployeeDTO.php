<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeDTO
{
    public function __construct(
        public int $id,
        public string $uuid,
        public int $userId,
        public string $email,
        public bool $isActive,
        public bool $isBanned,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
    ) {
    }
}