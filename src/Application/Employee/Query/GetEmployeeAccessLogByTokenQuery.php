<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

final readonly class GetEmployeeAccessLogByTokenQuery
{
    public function __construct(
        public string $token,
        public int $userId // For authorization check
    ) {}
}
