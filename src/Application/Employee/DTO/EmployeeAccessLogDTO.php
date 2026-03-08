<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

final readonly class EmployeeAccessLogDTO
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $token,
        public bool $isTerminated,
        public bool $isExpired,
        public string $expiresAt,
        public int $refreshCount,
        public int $requestsCount,
        public ?string $ipAddress,
        public ?string $userAgent,
        public string $createdAt,
        public string $updatedAt,
        public ?array $payload
    ) {}
}
