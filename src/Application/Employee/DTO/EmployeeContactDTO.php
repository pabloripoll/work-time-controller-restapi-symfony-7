<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeContactDTO
{
    public function __construct(
        public int $id,
        public int $employeeId,
        public ?string $postal = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $mobile = null,
    ) {
    }
}
