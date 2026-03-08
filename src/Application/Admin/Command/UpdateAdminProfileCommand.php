<?php

declare(strict_types=1);

namespace App\Application\Admin\Command;

final readonly class UpdateAdminProfileCommand
{
    public function __construct(
        public int $userId,
        public ?string $nickname = null,
        public ?string $avatar = null,
    ) {}
}
