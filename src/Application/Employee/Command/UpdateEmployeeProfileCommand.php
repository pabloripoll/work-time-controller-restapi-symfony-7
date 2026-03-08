<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

final readonly class UpdateEmployeeProfileCommand
{
    public function __construct(
        public int $userId,
        public ?string $name = null,
        public ?string $surname = null,
        public ?string $phoneNumber = null,
        public ?string $department = null,
        public ?string $birthDate = null,
        public ?string $currentPassword = null,
        public ?string $newPassword = null,
    ) {}
}
