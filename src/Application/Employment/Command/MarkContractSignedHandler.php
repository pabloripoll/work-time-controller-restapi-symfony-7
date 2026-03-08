<?php

namespace App\Application\Employment\Command;

use App\Application\Employment\Service\EmploymentAuditService;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use App\Domain\Employment\ValueObject\EmploymentActionKey;

readonly class MarkContractSignedHandler
{
    public function __construct(
        private EmploymentContractRepositoryInterface $contractRepo,
        private AdminRepositoryInterface $adminRepo,
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(MarkContractSignedCommand $command): void
    {
        $contract = $this->contractRepo->findById($command->contractId);
        $admin = $this->adminRepo->findById($command->adminId);

        $contract->markContractAsSigned();
        $this->contractRepo->save($contract);

        // Audit log
        $this->auditService->logContractAction(
            contract: $contract,
            actionKey: EmploymentActionKey::CONTRACT_SIGNED,
            admin: $admin
        );
    }
}
