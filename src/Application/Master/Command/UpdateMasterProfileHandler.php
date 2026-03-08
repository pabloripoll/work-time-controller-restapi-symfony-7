<?php

declare(strict_types=1);

namespace App\Application\Master\Command;

use App\Domain\Master\Repository\MasterRepositoryInterface;
use App\Domain\Master\Repository\MasterProfileRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;

readonly class UpdateMasterProfileHandler
{
    public function __construct(
        private MasterRepositoryInterface $masterRepository,
        private MasterProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(UpdateMasterProfileCommand $command): void
    {
        // Step 1: Get Master by user_id
        $master = $this->masterRepository->findByUserId($command->userId);

        if (! $master) {
            throw new EntityNotFoundException('Master not found for user');
        }

        // Step 2: Get Profile by master_id
        $profile = $this->profileRepository->findByMasterId($master->getId());

        if (! $profile) {
            throw new EntityNotFoundException('Master profile not found');
        }

        // Step 3: Update profile
        $profile->updateProfile($command->nickname, $command->avatar);
        $this->profileRepository->save($profile);
    }
}
