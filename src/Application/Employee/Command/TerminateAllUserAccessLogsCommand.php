<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

final readonly class TerminateAllUserAccessLogsCommand
{
    public function __construct(
        public int $userId,
        public bool $byAdmin = false // If admin is terminating another user's sessions
    ) {}
}
