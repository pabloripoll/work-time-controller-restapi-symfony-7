<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

final readonly class GetEmployeeAccessLogsQuery
{
    public function __construct(
        public int $userId,
        public bool $activeOnly = false
    ) {}
}
