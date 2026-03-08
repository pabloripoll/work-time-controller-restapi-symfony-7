<?php

namespace App\Application\Employment\DTO;

readonly class AuditLogDTO
{
    public function __construct(
        public int $id,
        public string $actionKey,
        public ?int $adminId,
        public ?string $adminNickname,
        public \DateTimeImmutable $createdAt
    ) {
    }
}
