<?php

namespace App\Application\Employment\Command;

use App\Application\Employment\Service\EmploymentAuditService;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use App\Domain\Employment\ValueObject\EmploymentActionKey;

readonly class AddExtraHoursHandler
{
    public function __construct(
        private EmploymentWorkdayRepositoryInterface $workdayRepo,
        private AdminRepositoryInterface $adminRepo,
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(AddExtraHoursCommand $command): void
    {
        $workday = $this->workdayRepo->findById($command->workdayId);
        if (! $workday) {
            throw new \DomainException("Workday not found");
        }

        $admin = $this->adminRepo->findById($command->adminId);
        if (! $admin) {
            throw new \DomainException("Admin not found");
        }

        // Add extra hours
        $workday->setHoursExtraFromString($command->extraHours);
        $this->workdayRepo->save($workday);

        // Audit log
        $this->auditService->logWorkdayAction(
            workday: $workday,
            actionKey: EmploymentActionKey::WORKDAY_HOURS_EXTRA_ADDED,
            admin: $admin
        );
    }
}
