<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

final readonly class TerminateAccessLogCommand
{
    public function __construct(
        public int $accessLogId,
        public int $userId // For authorization check
    ) {}
}
