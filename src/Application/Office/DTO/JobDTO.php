<?php

namespace App\Application\Office\DTO;

class JobDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $departmentId,
        public readonly string $departmentName,
        public readonly string $title,
        public readonly string $description,
        public readonly \DateTimeInterface $createdAt,
        public readonly \DateTimeInterface $updatedAt
    ) {
    }
}
