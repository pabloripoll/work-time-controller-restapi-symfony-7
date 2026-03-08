<?php

namespace App\Application\Employee\Query;

class GetEmployeeByIdQuery
{
    public function __construct(
        public readonly int $employeeId
    ) {
    }
}
