<?php

namespace App\Application\Office\Query;

class GetJobsByDepartmentQuery
{
    public function __construct(
        public readonly int $departmentId
    ) {
    }
}
