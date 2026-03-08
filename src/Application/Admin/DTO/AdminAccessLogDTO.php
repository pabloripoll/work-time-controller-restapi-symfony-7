<?php

declare(strict_types=1);

namespace App\Application\Admin\DTO;

final readonly class AdminAccessLogDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $token,
        public bool $isTerminated,
        public bool $isExpired,
        public \DateTimeImmutable $expiresAt,
        public int $refreshCount,
        public int $requestsCount,
        public ?string $ipAddress,
        public ?string $userAgent,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?array $payload
    ) {}
}
