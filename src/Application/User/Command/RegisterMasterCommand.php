<?php

declare(strict_types=1);

namespace App\Application\User\Command;

final readonly class RegisterMasterCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $nickname,
        public int $createdByUserId,
    ) {}
}
