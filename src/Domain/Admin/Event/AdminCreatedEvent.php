<?php

declare(strict_types=1);

namespace App\Domain\Admin\Event;

final readonly class AdminCreatedEvent
{
    public function __construct(
        public int $masterId,
        public string $email,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $masterId, string $email): self
    {
        return new self($masterId, $email, new \DateTimeImmutable());
    }
}
