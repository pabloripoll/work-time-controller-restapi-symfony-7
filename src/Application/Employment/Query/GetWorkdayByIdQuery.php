<?php

namespace App\Application\Employment\Query;

class GetWorkdayByIdQuery
{
    public function __construct(
        public readonly int $workdayId
    ) {
    }
}
