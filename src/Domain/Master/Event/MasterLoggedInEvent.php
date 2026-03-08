<?php

declare(strict_types=1);

namespace App\Domain\Master\Event;

final readonly class MasterLoggedInEvent
{
    public function __construct(
        public int $masterId,
        public string $ipAddress,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $masterId, string $ipAddress): self
    {
        return new self($masterId, $ipAddress, new \DateTimeImmutable());
    }
}
