<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

use App\Application\Master\DTO\MasterProfileDTO;
use App\Domain\Master\Repository\MasterRepositoryInterface;
use App\Domain\Master\Repository\MasterProfileRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;

readonly class GetMasterProfileHandler
{
    public function __construct(
        private MasterRepositoryInterface $masterRepository,
        private MasterProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(GetMasterProfileQuery $query): MasterProfileDTO
    {
        $master = $this->masterRepository->findByUserId($query->userId);
        if (! $master) {
            throw new EntityNotFoundException('Master not found for user');
        }

        $profile = $this->profileRepository->findByMasterId($master->getId());
        if (! $profile) {
            throw new EntityNotFoundException('Master profile not found');
        }

        // Step 3: Build DTO with joined data
        return new MasterProfileDTO(
            id: $profile->getId(),
            masterId: $master->getId(),
            userId: $master->getUser()->getId(),
            email: $master->getUser()->getEmail(),
            nickname: $profile->getNickname(),
            avatar: $profile->getAvatar(),
            createdAt: $profile->getCreatedAt(),
            updatedAt: $profile->getUpdatedAt()
        );
    }
}
