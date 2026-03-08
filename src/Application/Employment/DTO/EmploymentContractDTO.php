<?php

namespace App\Application\Employment\DTO;

use App\Domain\Employment\Entity\EmploymentContract;

final readonly class EmploymentContractDTO
{
    public function __construct(
        public int $id,
        public int $contractTypeId,
        public string $contractTypeName,
        public int $userId,
        public int $daysPerMonth,
        public int $daysPerWeek,
        public int $hoursPerDay,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt,
        public int $createdByUserId,
        public ?int $updatedByUserId
    ) {
    }

    public static function fromEntity(EmploymentContract $entity): self
    {
        return new self(
            id: $entity->getId(),
            contractTypeId: $entity->getContractType()->getId(),
            contractTypeName: $entity->getContractType()->getTitle() ?? 'N/A',
            userId: $entity->getUser()->getId(),
            daysPerMonth: $entity->getDaysPerMonth(),
            daysPerWeek: $entity->getDaysPerWeek(),
            hoursPerDay: $entity->getHoursPerDay(),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt(),
            deletedAt: $entity->getDeletedAt(),
            createdByUserId: $entity->getCreatedByUserId(),
            updatedByUserId: $entity->getUpdatedByUserId()
        );
    }
}
