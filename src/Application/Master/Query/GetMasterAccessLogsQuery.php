<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

final readonly class GetMasterAccessLogsQuery
{
    public function __construct(
        public int $userId,
        public bool $activeOnly = false
    ) {}
}
