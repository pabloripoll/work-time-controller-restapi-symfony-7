<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UpdateEmployeeProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(UpdateEmployeeProfileCommand $command): void
    {
        $userId = $command->userId;

        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            throw new DomainException('User not found');
        }

        if (! $user->isEmployee()) {
            throw new DomainException('User is not a member');
        }

        // Update password (requires current password verification)
        if ($command->newPassword !== null) {
            if ($command->currentPassword === null) {
                throw new DomainException('Current password is required to change password');
            }

            if (! $this->passwordHasher->isPasswordValid($user, $command->currentPassword)) {
                throw new DomainException('Current password is incorrect');
            }

            $hashedPassword = $this->passwordHasher->hashPassword($user, $command->newPassword);
            $user->updatePassword($hashedPassword);
        }

        // Save the user
        $this->userRepository->save($user);
    }
}
