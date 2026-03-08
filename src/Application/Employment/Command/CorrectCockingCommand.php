<?php

namespace App\Application\Employment\Command;

readonly class CorrectClockingCommand
{
    public function __construct(
        public int $clockingId,
        public \DateTimeImmutable $correctedTime,
        public int $adminId
    ) {
    }
}
