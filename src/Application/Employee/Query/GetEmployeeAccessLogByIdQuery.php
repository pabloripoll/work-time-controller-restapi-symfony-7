<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

final readonly class GetEmployeeAccessLogByIdQuery
{
    public function __construct(
        public int $accessLogId,
        public int $userId // For authorization check
    ) {}
}
