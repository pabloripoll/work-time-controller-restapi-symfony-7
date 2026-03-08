<?php

declare(strict_types=1);

namespace App\Application\Employee\Service;

use App\Domain\Employee\Entity\EmployeeAccessLog;
use App\Domain\Employee\Repository\EmployeeAccessLogRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class EmployeeAuthenticationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeRepositoryInterface $employeeRepository,
        private EmployeeAccessLogRepositoryInterface $accessLogRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * Authenticate employee and return User if successful
     */
    public function authenticate(Email $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email->value);

        if (! $user || !$user->isEmployee()) {
            return null;
        }

        if (! $this->passwordHasher->isPasswordValid($user, $password)) {
            return null;
        }

        // Check if employee is active and not banned
        $employee = $this->employeeRepository->findByUserId($user->getId());

        if (! $employee || !$employee->isActive() || $employee->isBanned()) {
            return null;
        }

        return $user;
    }

    /**
     * Log successful authentication
     */
    public function logAccess(
        User $user,
        string $token,
        \DateTimeImmutable $expiresAt,
        string $ipAddress,
        ?string $userAgent = null
    ): EmployeeAccessLog {
        $accessLog = EmployeeAccessLog::create(
            user: $user,
            token: $token,
            expiresAt: $expiresAt,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            payload: [
                'login_time' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                'ip' => $ipAddress,
            ]
        );

        $this->accessLogRepository->save($accessLog);

        return $accessLog;
    }

    /**
     * Terminate all active sessions for employee
     */
    public function terminateAllSessions(int $userId): void
    {
        $this->accessLogRepository->terminateAllByUserId($userId);
    }
}
