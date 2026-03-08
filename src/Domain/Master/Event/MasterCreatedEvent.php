<?php

declare(strict_types=1);

namespace App\Domain\Master\Event;

final readonly class MasterCreatedEvent
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
