<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

/**
 * Master use case: get all member access logs
 */
final readonly class GetAllMasterAccessLogsQuery
{
    public function __construct(
        public bool $activeOnly = false,
        public ?int $userId = null // Optional filter by user
    ) {}
}
