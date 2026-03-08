<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

final readonly class UserCreatedEvent
{
    public function __construct(
        public int $userId,
        public string $email,
        public string $role,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $userId, string $email, string $role): self
    {
        return new self($userId, $email, $role, new \DateTimeImmutable());
    }
}
