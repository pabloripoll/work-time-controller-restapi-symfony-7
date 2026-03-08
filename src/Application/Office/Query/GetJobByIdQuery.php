<?php

namespace App\Application\Office\Query;

class GetJobByIdQuery
{
    public function __construct(
        public readonly int $departmentId,
        public readonly int $jobId
    ) {
    }
}
