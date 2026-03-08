<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\AuditLogDTO;
use App\Application\Employment\Service\EmploymentAuditService;

readonly class GetContractAuditTrailHandler
{
    public function __construct(
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(GetContractAuditTrailQuery $query): array
    {
        $logs = $this->auditService->getContractAuditTrail($query->contractId);

        return array_map(
            fn($log) => new AuditLogDTO(
                id: $log->getId(),
                actionKey: $log->getActionKey(),
                adminId: $log->getAdminId(),
                adminNickname: $log->getAdmin()?->getProfile()->getNickname(),
                createdAt: $log->getCreatedAt()
            ),
            $logs
        );
    }
}
