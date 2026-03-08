<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

use App\Application\Employee\DTO\EmployeeAccessLogDTO;
use App\Domain\Employee\Repository\EmployeeAccessLogRepositoryInterface;

final readonly class GetEmployeeAccessLogsHandler
{
    public function __construct(
        private EmployeeAccessLogRepositoryInterface $accessLogRepository
    ) {}

    /**
     * @return EmployeeAccessLogDTO[]
     */
    public function __invoke(GetEmployeeAccessLogsQuery $query): array
    {
        $userId = $query->userId;

        $accessLogs = $query->activeOnly
            ? $this->accessLogRepository->findActiveByUserId($userId)
            : $this->accessLogRepository->findByUserId($userId);

        return array_map(
            fn($accessLog) => new EmployeeAccessLogDTO(
                id: $accessLog->getId(),
                userId: $accessLog->getUserId(),
                token: $accessLog->getToken(),
                isTerminated: $accessLog->isTerminated(),
                isExpired: $accessLog->isExpired(),
                expiresAt: $accessLog->getExpiresAt()->format(\DateTimeInterface::ATOM),
                refreshCount: $accessLog->getRefreshCount(),
                requestsCount: $accessLog->getRequestsCount(),
                ipAddress: $accessLog->getIpAddress(),
                userAgent: $accessLog->getUserAgent(),
                createdAt: $accessLog->getCreatedAt()->format(\DateTimeInterface::ATOM),
                updatedAt: $accessLog->getUpdatedAt()->format(\DateTimeInterface::ATOM),
                payload: $accessLog->getPayload()
            ),
            $accessLogs
        );
    }
}
