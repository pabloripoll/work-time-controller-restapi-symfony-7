<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

use App\Application\Employee\DTO\EmployeeAccessLogDTO;
use App\Domain\Employee\Repository\EmployeeAccessLogRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use App\Domain\Shared\Exception\ValidationException;

final readonly class GetEmployeeAccessLogByIdHandler
{
    public function __construct(
        private EmployeeAccessLogRepositoryInterface $accessLogRepository
    ) {}

    public function __invoke(GetEmployeeAccessLogByIdQuery $query): EmployeeAccessLogDTO
    {
        $accessLog = $this->accessLogRepository->findById($query->accessLogId);

        if ($accessLog === null) {
            throw new EntityNotFoundException('Access log not found');
        }

        // Authorization check
        if ((string)$accessLog->getUserId() !== $query->userId) {
            throw new ValidationException('Unauthorized to view this access log');
        }

        return new EmployeeAccessLogDTO(
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
        );
    }
}
