<?php

namespace App\Application\Employment\Command;

readonly class ClockOutCommand
{
    public function __construct(
        public int $employeeId,
        public ?\DateTimeImmutable $clockTime = null // null = now
    ) {
    }
}
