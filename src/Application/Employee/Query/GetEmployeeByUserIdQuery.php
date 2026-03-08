<?php

namespace App\Application\Employee\Query;

class GetEmployeeByUserIdQuery
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
