<?php

declare(strict_types=1);

namespace App\Application\Master\Command;

use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;

final readonly class TerminateAllUserAccessLogsHandler
{
    public function __construct(
        private MasterAccessLogRepositoryInterface $accessLogRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(TerminateAllUserAccessLogsCommand $command): int
    {
        $userId = $command->userId;

        // Verify user exists
        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            throw new EntityNotFoundException('User not found');
        }

        // Get all active access logs
        $accessLogs = $this->accessLogRepository->findActiveByUserId($userId);

        foreach ($accessLogs as $accessLog) {
            $accessLog->terminate();
            $this->accessLogRepository->save($accessLog);
        }

        return count($accessLogs);
    }
}
