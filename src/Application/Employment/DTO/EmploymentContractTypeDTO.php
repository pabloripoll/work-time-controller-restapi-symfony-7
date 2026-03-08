<?php

namespace App\Application\Employment\DTO;

use App\Domain\Employment\Entity\EmploymentContractType;

final readonly class EmploymentContractTypeDTO
{
    public function __construct(
        public int $id,
        public ?string $title,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt
    ) {
    }

    public static function fromEntity(EmploymentContractType $entity): self
    {
        return new self(
            id: $entity->getId(),
            title: $entity->getTitle(),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt(),
            deletedAt: $entity->getDeletedAt()
        );
    }
}
