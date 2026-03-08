<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

final readonly class GetMasterAccessLogByIdQuery
{
    public function __construct(
        public int $accessLogId,
        public int $userId // For authorization check
    ) {}
}
