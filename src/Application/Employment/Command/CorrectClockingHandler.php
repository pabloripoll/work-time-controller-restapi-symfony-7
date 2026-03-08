<?php

namespace App\Application\Employment\Command;

use App\Application\Employment\Service\EmploymentAuditService;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;
use App\Domain\Employment\ValueObject\EmploymentActionKey;

readonly class CorrectClockingHandler
{
    public function __construct(
        private EmploymentWorkdayClockingRepositoryInterface $clockingRepo,
        private AdminRepositoryInterface $adminRepo,
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(CorrectClockingCommand $command): void
    {
        $clocking = $this->clockingRepo->findById($command->clockingId);
        if (! $clocking) {
            throw new \DomainException("Clocking not found");
        }

        $admin = $this->adminRepo->findById($command->adminId);
        if (! $admin) {
            throw new \DomainException("Admin not found");
        }

        // Correct the time
        $clocking->correctTime($command->correctedTime);
        $this->clockingRepo->save($clocking);

        // Audit log
        $this->auditService->logClockingAction(
            clocking: $clocking,
            actionKey: EmploymentActionKey::CLOCKING_TIME_CORRECTED,
            admin: $admin
        );
    }
}
