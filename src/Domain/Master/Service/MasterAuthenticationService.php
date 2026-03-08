<?php

declare(strict_types=1);

namespace App\Domain\Master\Service;

use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class MasterAuthenticationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MasterAccessLogRepositoryInterface $accessLogRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function authenticate(Email $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email->value);

        if (! $user || !$user->isMaster()) {
            return false;
        }

        return $this->passwordHasher->isPasswordValid($user, $password);
    }
}
