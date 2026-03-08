<?php

namespace App\Application\Employment\DTO;

use App\Domain\Employment\Entity\EmploymentWorkday;

final readonly class EmploymentWorkdayDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $contractId,
        public ?\DateTimeImmutable $startsDate,
        public ?\DateTimeImmutable $endsDate,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt,
        public int $createdByUserId,
        public ?int $updatedByUserId
    ) {
    }

    public static function fromEntity(EmploymentWorkday $entity): self
    {
        return new self(
            id: $entity->getId(),
            userId: $entity->getUser()->getId(),
            contractId: $entity->getContract()->getId(),
            startsDate: $entity->getStartsDate(),
            endsDate: $entity->getEndsDate(),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt(),
            deletedAt: $entity->getDeletedAt(),
            createdByUserId: $entity->getCreatedByUserId(),
            updatedByUserId: $entity->getUpdatedByUserId()
        );
    }
}
