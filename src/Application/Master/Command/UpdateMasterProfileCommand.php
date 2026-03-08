<?php

declare(strict_types=1);

namespace App\Application\Master\Command;

final readonly class UpdateMasterProfileCommand
{
    public function __construct(
        public int $userId,
        public ?string $nickname = null,
        public ?string $avatar = null,
    ) {}
}
