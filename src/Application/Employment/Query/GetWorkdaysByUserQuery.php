<?php

namespace App\Application\Employment\Query;

class GetWorkdaysByUserQuery
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
