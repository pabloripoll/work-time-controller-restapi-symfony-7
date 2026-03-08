<?php

namespace App\Application\Employment\Command;

readonly class AddExtraHoursCommand
{
    public function __construct(
        public int $workdayId,
        public string $extraHours, // Format: "HH:MM"
        public int $adminId
    ) {
    }
}
