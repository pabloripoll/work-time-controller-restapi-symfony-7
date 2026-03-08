<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

use App\Application\Master\DTO\MasterDTO;
use App\Domain\Master\Repository\MasterRepositoryInterface;
use App\Domain\Master\Repository\MasterProfileRepositoryInterface;

readonly class GetAllMasterHandler
{
    public function __construct(
        private MasterRepositoryInterface $masterRepository,
        private MasterProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(GetAllMasterQuery $query): array
    {
        // Option 1: Simple (N+1 queries)
        $masters = $this->masterRepository->findAll();

        // Option 2: Optimized with JOIN (1 query)
        // $masters = $this->masterRepository->findAllWithProfiles();

        return array_map(function ($master) {
            // For Option 1, fetch profile separately
            $profile = $this->profileRepository->findByMasterId($master->getId());

            // For Option 2, profile is already loaded via JOIN
            // $profile = $master->getProfile();

            return new MasterDTO(
                id: $master->getId(),
                userId: $master->getUser()->getId(),
                email: $master->getUser()->getEmail(),
                nickname: $profile?->getNickname(),
                avatar: $profile?->getAvatar(),
                isActive: $master->getIsActive(),
                isBanned: $master->getIsBanned(),
                createdAt: $master->getCreatedAt(),
                updatedAt: $master->getUpdatedAt()
            );
        }, $masters);
    }
}
