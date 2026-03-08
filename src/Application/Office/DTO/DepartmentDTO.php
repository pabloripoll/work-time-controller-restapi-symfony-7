<?php

namespace App\Application\Office\DTO;

class DepartmentDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly \DateTimeInterface $createdAt,
        public readonly \DateTimeInterface $updatedAt
    ) {
    }
}
