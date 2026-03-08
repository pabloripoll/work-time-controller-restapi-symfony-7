<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeWorkplaceDTO
{
    public function __construct(
        public int $id,
        public int $employeeId,
        public ?int $departmentId = null,
        public ?string $departmentName = null,
        public ?int $jobId = null,
        public ?string $jobTitle = null,
        public ?\DateTimeInterface $createdAt = null,
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }
}
