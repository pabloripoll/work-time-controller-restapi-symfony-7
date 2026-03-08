<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

final readonly class GetMasterAccessLogByTokenQuery
{
    public function __construct(
        public string $token,
        public int $userId // For authorization check
    ) {}
}
