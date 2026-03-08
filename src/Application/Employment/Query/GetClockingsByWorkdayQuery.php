<?php

namespace App\Application\Employment\Query;

class GetClockingsByWorkdayQuery
{
    public function __construct(
        public readonly int $workdayId
    ) {
    }
}
