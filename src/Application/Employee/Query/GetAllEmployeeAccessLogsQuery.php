<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

/**
 * Admin use case: get all employee access logs
 */
final readonly class GetAllEmployeeAccessLogsQuery
{
    public function __construct(
        public bool $activeOnly = false,
        public ?int $userId = null // Optional filter by user
    ) {}
}
