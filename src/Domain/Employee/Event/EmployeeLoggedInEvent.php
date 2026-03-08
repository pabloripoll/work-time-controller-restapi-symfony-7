<?php

declare(strict_types=1);

namespace App\Domain\Employee\Event;

final readonly class EmployeeLoggedInEvent
{
    public function __construct(
        public int $employeeId,
        public string $ipAddress,
        public \DateTimeImmutable $occurredOn
    ) {}

    public static function create(int $employeeId, string $ipAddress): self
    {
        return new self($employeeId, $ipAddress, new \DateTimeImmutable());
    }
}
