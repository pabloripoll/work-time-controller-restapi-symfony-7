<?php

namespace App\Application\Employment\Command;

use App\Application\Employment\Service\EmploymentAuditService;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use App\Domain\Employment\ValueObject\EmploymentActionKey;

readonly class DeleteWorkdayHandler
{
    public function __construct(
        private EmploymentWorkdayRepositoryInterface $workdayRepo,
        private AdminRepositoryInterface $adminRepo,
        private EmploymentAuditService $auditService
    ) {
    }

    public function __invoke(DeleteWorkdayCommand $command): void
    {
        $workday = $this->workdayRepo->findById($command->workdayId);
        $admin = $this->adminRepo->findById($command->adminId);

        // Soft delete
        $workday->softDelete();
        $this->workdayRepo->save($workday);

        // Audit log
        $this->auditService->logWorkdayAction(
            workday: $workday,
            actionKey: EmploymentActionKey::WORKDAY_DELETED,
            admin: $admin
        );
    }
}
