<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

use App\Application\Master\DTO\MasterAccessLogDTO;
use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;

final readonly class GetMasterAccessLogsHandler
{
    public function __construct(
        private MasterAccessLogRepositoryInterface $accessLogRepository
    ) {}

    /**
     * @return MasterAccessLogDTO[]
     */
    public function __invoke(GetMasterAccessLogsQuery $query): array
    {
        $userId = $query->userId;

        $accessLogs = $query->activeOnly
            ? $this->accessLogRepository->findActiveByUserId($userId)
            : $this->accessLogRepository->findByUserId($userId);

        return array_map(
            fn($accessLog) => new MasterAccessLogDTO(
                id: $accessLog->getId(),
                userId: $accessLog->getUserId(),
                token: $accessLog->getToken(),
                isTerminated: $accessLog->isTerminated(),
                isExpired: $accessLog->isExpired(),
                expiresAt: $accessLog->getExpiresAt(),
                refreshCount: $accessLog->getRefreshCount(),
                requestsCount: $accessLog->getRequestsCount(),
                ipAddress: $accessLog->getIpAddress(),
                userAgent: $accessLog->getUserAgent(),
                createdAt: $accessLog->getCreatedAt(),
                updatedAt: $accessLog->getUpdatedAt(),
                payload: $accessLog->getPayload()
            ),
            $accessLogs
        );
    }
}
