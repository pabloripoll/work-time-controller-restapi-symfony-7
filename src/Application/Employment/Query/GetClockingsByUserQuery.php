<?php

namespace App\Application\Employment\Query;

class GetClockingsByUserQuery
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
