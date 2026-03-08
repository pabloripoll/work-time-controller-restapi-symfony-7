<?php

namespace App\Application\Employment\Command;

readonly class UpdateEmploymentContractCommand
{
    public function __construct(
        public int $contractId,
        public int $daysPerMonth,
        public int $daysPerWeek,
        public int $hoursPerDay,
        public int $adminId
    ) {}
}
