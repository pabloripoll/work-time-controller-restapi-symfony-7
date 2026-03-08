<?php

declare(strict_types=1);

namespace App\Application\Master\Command;

final readonly class TerminateAllUserAccessLogsCommand
{
    public function __construct(
        public int $userId,
        public bool $byMaster = false // If admin is terminating another user's sessions
    ) {}
}
