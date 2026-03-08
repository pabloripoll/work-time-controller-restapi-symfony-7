<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

final readonly class GetAdminAccessLogsQuery
{
    public function __construct(
        public int $userId,
        public bool $activeOnly = false
    ) {}
}
