<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

final readonly class IncrementAccessLogRequestCountCommand
{
    public function __construct(public string $token) {}
}
