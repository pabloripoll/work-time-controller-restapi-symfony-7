<?php

declare(strict_types=1);

namespace App\Application\Master\Query;

use App\Application\Master\DTO\MasterAccessLogDTO;
use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use App\Domain\Shared\Exception\ValidationException;

final readonly class GetMasterAccessLogByIdHandler
{
    public function __construct(
        private MasterAccessLogRepositoryInterface $accessLogRepository
    ) {}

    public function __invoke(GetMasterAccessLogByIdQuery $query): MasterAccessLogDTO
    {
        $accessLog = $this->accessLogRepository->findById($query->accessLogId);

        if ($accessLog === null) {
            throw new EntityNotFoundException('Access log not found');
        }

        // Authorization check
        if ((string)$accessLog->getUserId() !== $query->userId) {
            throw new ValidationException('Unauthorized to view this access log');
        }

        return new MasterAccessLogDTO(
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
        );
    }
}
