<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

final readonly class UserLoggedInEvent
{
    public function __construct(
        public int $userId,
        public string $ipAddress,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $userId, string $ipAddress): self
    {
        return new self($userId, $ipAddress, new \DateTimeImmutable());
    }
}
