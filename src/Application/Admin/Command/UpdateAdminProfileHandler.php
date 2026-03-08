<?php

declare(strict_types=1);

namespace App\Application\Admin\Command;

use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Admin\Repository\AdminProfileRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;

readonly class UpdateAdminProfileHandler
{
    public function __construct(
        private AdminRepositoryInterface $masterRepository,
        private AdminProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(UpdateAdminProfileCommand $command): void
    {
        // Step 1: Get Admin by user_id
        $master = $this->masterRepository->findByUserId($command->userId);

        if (! $master) {
            throw new EntityNotFoundException('Admin not found for user');
        }

        // Step 2: Get Profile by master_id
        $profile = $this->profileRepository->findByAdminId($master->getId());

        if (! $profile) {
            throw new EntityNotFoundException('Admin profile not found');
        }

        // Step 3: Update profile
        $profile->updateProfile($command->nickname, $command->avatar);
        $this->profileRepository->save($profile);
    }
}
