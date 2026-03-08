<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

enum UserRole: string
{
    case MASTER     = 'ROLE_MASTER';
    case ADMIN      = 'ROLE_ADMIN';
    case EMPLOYEE   = 'ROLE_EMPLOYEE';

    public function toString(): string
    {
        return $this->value;
    }
}
