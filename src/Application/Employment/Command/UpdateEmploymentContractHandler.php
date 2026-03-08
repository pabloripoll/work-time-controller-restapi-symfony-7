<?php

namespace App\Application\Employment\Command;

use App\Application\Employment\Service\EmploymentAuditService;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use App\Domain\Employment\ValueObject\EmploymentActionKey;

readonly class UpdateEmploymentContractHandler
{
    public function __construct(
        private EmploymentContractRepositoryInterface $contractRepo,
        private AdminRepositoryInterface $adminRepo,
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(UpdateEmploymentContractCommand $command): void
    {
        $contract = $this->contractRepo->findById($command->contractId);
        if (! $contract) {
            throw new \DomainException("Contract not found");
        }

        $admin = $this->adminRepo->findById($command->adminId);
        if (! $admin) {
            throw new \DomainException("Admin not found");
        }

        // Update contract
        $contract->updateTerms(
            daysPerMonth: $command->daysPerMonth,
            daysPerWeek: $command->daysPerWeek,
            hoursPerDay: $command->hoursPerDay
        );

        $this->contractRepo->save($contract);

        // Audit log
        $this->auditService->logContractAction(
            contract: $contract,
            actionKey: EmploymentActionKey::CONTRACT_UPDATED,
            admin: $admin
        );
    }
}