<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

use App\Application\Admin\DTO\AdminAccessLogDTO;
use App\Domain\Admin\Repository\AdminAccessLogRepositoryInterface;

final readonly class GetAdminAccessLogsHandler
{
    public function __construct(
        private AdminAccessLogRepositoryInterface $accessLogRepository
    ) {}

    /**
     * @return AdminAccessLogDTO[]
     */
    public function __invoke(GetAdminAccessLogsQuery $query): array
    {
        $userId = $query->userId;

        $accessLogs = $query->activeOnly
            ? $this->accessLogRepository->findActiveByUserId($userId)
            : $this->accessLogRepository->findByUserId($userId);

        return array_map(
            fn($accessLog) => new AdminAccessLogDTO(
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
