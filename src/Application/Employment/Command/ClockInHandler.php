<?php

namespace App\Application\Employment\Command;

use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Employment\Entity\EmploymentWorkday;
use App\Domain\Employment\Entity\EmploymentWorkdayClocking;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;

readonly class ClockInHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepo,
        private EmploymentContractRepositoryInterface $contractRepo,
        private EmploymentWorkdayRepositoryInterface $workdayRepo,
        private EmploymentWorkdayClockingRepositoryInterface $clockingRepo
    ) {
    }

    public function __invoke(ClockInCommand $command): EmploymentWorkdayClocking
    {
        $employee = $this->employeeRepo->findById($command->employeeId);
        if (! $employee) {
            throw new \DomainException("Employee not found");
        }

        // Get active contract
        $contract = $this->contractRepo->findActiveByEmployeeId($command->employeeId);
        if (! $contract) {
            throw new \DomainException("No active contract found");
        }

        $clockTime = $command->clockTime ?? new \DateTimeImmutable();

        // Find or create today's workday
        $workday = $this->workdayRepo->findTodayByEmployeeId($command->employeeId);

        if (! $workday) {
            // Create new workday
            $workday = EmploymentWorkday::create(
                employee: $employee,
                contract: $contract,
                startsDate: $clockTime,
                endsDate: $this->calculateEndDate($clockTime, $contract->getHoursPerDay()),
                createdByUserId: $employee->getUser()->getId()
            );
            $this->workdayRepo->save($workday);
        }

        // Create clock-in record
        $clocking = EmploymentWorkdayClocking::clockIn(
            workday: $workday,
            employee: $employee,
            clockTime: $command->clockTime // null = now
        );

        $this->clockingRepo->save($clocking);

        return $clocking;
    }

    private function calculateEndDate(\DateTimeImmutable $startDate, int $hoursPerDay): \DateTimeImmutable
    {
        return $startDate->modify("+{$hoursPerDay} hours");
    }
}
