<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        public string $role
    ) {}
}
