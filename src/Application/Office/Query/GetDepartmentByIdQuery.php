<?php

namespace App\Application\Office\Query;

class GetDepartmentByIdQuery
{
    public function __construct(
        public readonly int $departmentId
    ) {
    }
}
