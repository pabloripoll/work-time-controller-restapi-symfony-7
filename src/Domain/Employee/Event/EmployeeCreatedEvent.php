<?php

declare(strict_types=1);

namespace App\Domain\Employee\Event;

final readonly class EmployeeCreatedEvent
{
    public function __construct(
        public int $employeeId,
        public string $email,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $employeeId, string $email): self
    {
        return new self($employeeId, $email, new \DateTimeImmutable());
    }
}
