<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

use App\Application\Admin\DTO\AdminDTO;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Admin\Repository\AdminProfileRepositoryInterface;

readonly class GetAllAdminHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private AdminProfileRepositoryInterface $profileRepository
    ) {
    }

    public function __invoke(GetAllAdminQuery $query): array
    {
        // Option 1: Simple (N+1 queries)
        $admins = $this->adminRepository->findAll();

        // Option 2: Optimized with JOIN (1 query)
        // $admins = $this->adminRepository->findAllWithProfiles();

        return array_map(function ($admin) {
            // For Option 1, fetch profile separately
            $profile = $this->profileRepository->findByAdminId($admin->getId());

            // For Option 2, profile is already loaded via JOIN
            // $profile = $admin->getProfile();

            return new AdminDTO(
                id: $admin->getId(),
                userId: $admin->getUser()->getId(),
                email: $admin->getUser()->getEmail(),
                nickname: $profile?->getNickname(),
                avatar: $profile?->getAvatar(),
                isActive: $admin->getIsActive(),
                isBanned: $admin->getIsBanned(),
                isSuperadmin: $admin->getIsSuperadmin(),
                employeeId: $admin->getEmployeeId(),
                createdAt: $admin->getCreatedAt(),
                updatedAt: $admin->getUpdatedAt()
            );
        }, $admins);
    }
}
