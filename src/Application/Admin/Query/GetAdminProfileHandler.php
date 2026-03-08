<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

use App\Application\Admin\DTO\AdminProfileDTO;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Admin\Repository\AdminProfileRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;

readonly class GetAdminProfileHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private AdminProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(GetAdminProfileQuery $query): AdminProfileDTO
    {
        $admin = $this->adminRepository->findByUserId($query->userId);
        if (! $admin) {
            throw new EntityNotFoundException('Admin not found for user');
        }

        $profile = $this->profileRepository->findByAdminId($admin->getId());
        if (! $profile) {
            throw new EntityNotFoundException('Admin profile not found');
        }

        // Step 3: Build DTO with joined data
        return new AdminProfileDTO(
            id: $profile->getId(),
            adminId: $admin->getId(),
            userId: $admin->getUser()->getId(),
            email: $admin->getUser()->getEmail(),
            nickname: $profile->getNickname(),
            avatar: $profile->getAvatar(),
            createdAt: $profile->getCreatedAt(),
            updatedAt: $profile->getUpdatedAt()
        );
    }
}
