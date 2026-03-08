<?php

declare(strict_types=1);

namespace App\Application\Master\DTO;

use App\Domain\Shared\ValueObject\Email;

final readonly class MasterProfileDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $masterId,
        public Email $email,
        public string $nickname,
        public ?string $avatar,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt
    ) {}
}
