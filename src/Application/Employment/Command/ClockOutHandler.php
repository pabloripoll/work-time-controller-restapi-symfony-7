<?php

namespace App\Application\Employment\Command;

use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Employment\Entity\EmploymentWorkdayClocking;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;
use App\Domain\Employment\Entity\EmploymentWorkday;

readonly class ClockOutHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepo,
        private EmploymentWorkdayRepositoryInterface $workdayRepo,
        private EmploymentWorkdayClockingRepositoryInterface $clockingRepo
    ) {
    }

    public function __invoke(ClockOutCommand $command): EmploymentWorkdayClocking
    {
        $employee = $this->employeeRepo->findById($command->employeeId);
        if (! $employee) {
            throw new \DomainException("Employee not found");
        }

        // Find today's workday
        $workday = $this->workdayRepo->findTodayByEmployeeId($command->employeeId);
        if (! $workday) {
            throw new \DomainException("No active workday found. Please clock in first.");
        }

        $clockTime = $command->clockTime ?? new \DateTimeImmutable();

        // Create clock-out record
        $clocking = EmploymentWorkdayClocking::clockOut(
            workday: $workday,
            employee: $employee,
            clockTime: $clockTime
        );

        $this->clockingRepo->save($clocking);

        // Update workday hours_made
        $this->updateWorkdayHoursMade($workday);

        return $clocking;
    }

    private function updateWorkdayHoursMade(EmploymentWorkday $workday): void
    {
        $totalMinutes = 0;
        $clockings = $workday->getClockings();

        $clockInTime = null;
        foreach ($clockings as $clocking) {
            if ($clocking->isClockIn()) {
                $clockInTime = $clocking->getCreatedAt();
            } elseif ($clocking->isClockOut() && $clockInTime) {
                $diff = $clocking->getCreatedAt()->getTimestamp() - $clockInTime->getTimestamp();
                $totalMinutes += ($diff / 60);
                $clockInTime = null;
            }
        }

        // Convert minutes to time format
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $timeString = sprintf('%02d:%02d:00', $hours, $minutes);

        $workday->setHoursMadeFromString($timeString);
        $this->workdayRepo->save($workday);
    }
}
