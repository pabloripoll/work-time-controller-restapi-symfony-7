<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeProfileDTO
{
    public function __construct(
        public int $id,
        public int $employeeId,
        public string $name,
        public string $surname,
        public ?string $fullName = null,
        public ?\DateTimeImmutable $birthdate = null,
    ) {
    }
}
