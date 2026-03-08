<?php

namespace App\Application\Employment\DTO;

use App\Domain\Employment\Entity\EmploymentWorkdayClocking;

final readonly class EmploymentWorkdayClockingDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $workdayId,
        public bool $clockIn,
        public bool $clockOut,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt,
        public int $createdByUserId,
        public ?int $updatedByUserId
    ) {
    }

    public static function fromEntity(EmploymentWorkdayClocking $entity): self
    {
        return new self(
            id: $entity->getId(),
            userId: $entity->getUser()->getId(),
            workdayId: $entity->getWorkday()->getId(),
            clockIn: $entity->isClockIn(),
            clockOut: $entity->isClockOut(),
            createdAt: $entity->getCreatedAt(),
            updatedAt: $entity->getUpdatedAt(),
            deletedAt: $entity->getDeletedAt(),
            createdByUserId: $entity->getCreatedByUserId(),
            updatedByUserId: $entity->getUpdatedByUserId()
        );
    }
}
