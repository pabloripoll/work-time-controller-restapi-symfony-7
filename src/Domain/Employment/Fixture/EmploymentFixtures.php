<?php

declare(strict_types=1);

namespace App\Domain\Employment\Fixture;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Admin\Fixture\AdminFixtures;
use App\Domain\Employee\Entity\Employee;
use App\Domain\Employee\Fixture\EmployeeFixtures;
use App\Domain\Employment\Entity\EmploymentContract;
use App\Domain\Employment\Entity\EmploymentContractType;
use App\Domain\Employment\Entity\EmploymentWorkday;
use App\Domain\Employment\Entity\EmploymentWorkdayClocking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EmploymentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // 0. Get the superadmin
        $admin = $this->getAdmin($manager);

        // 1. Create or find contract types
        $contractTypes = $this->createContractTypes($manager);

        // 2. Get employees
        $employees = $this->getEmployees();

        // 3. Create contracts and workdays for each employee
        foreach ($employees as $employee) {
            $this->createEmploymentData($manager, $employee, $admin, $contractTypes);
        }

        $manager->flush();
    }

    private function getAdmin(ObjectManager $manager): Admin
    {
        // Fallback: find any active admin
        $adminRepo = $manager->getRepository(Admin::class);
        $admin = $adminRepo->findOneBy(['is_active' => true]);

        if (! $admin) {
            throw new \RuntimeException(
                'No active admin found. Please run AdminFixtures first.'
            );
        }

        return $admin;
    }

    private function createContractTypes(ObjectManager $manager): array
    {
        $types = [
            'Permanent' => 'Full-time permanent employment',
            'Temporary' => 'Fixed-term temporary contract',
            'Internship' => 'Internship or training program',
            'Part-Time' => 'Part-time employment',
            'Contractor' => 'Independent contractor',
        ];

        $contractTypes = [];
        $repo = $manager->getRepository(EmploymentContractType::class);

        foreach ($types as $title => $description) {
            $existing = $repo->findOneBy(['title' => $title]);

            if ($existing) {
                echo "  ✓ Contract type '{$title}' already exists\n";
                $contractTypes[$title] = $existing;
            } else {
                $contractType = EmploymentContractType::create($title, $description);
                $manager->persist($contractType);
                $contractTypes[$title] = $contractType;
                echo "  + Created contract type '{$title}'\n";
            }
        }

        $manager->flush();

        return $contractTypes;
    }

    private function getEmployees(): array
    {
        $employees = [];
        for ($i = 0; $i < EmployeeFixtures::TOTAL_EMPLOYEES; $i++) {
            $employees[] = $this->getReference("employee_{$i}", Employee::class);
        }
        return $employees;
    }

    private function createEmploymentData(
        ObjectManager $manager,
        Employee $employee,
        Admin $admin,
        array $contractTypes
    ): void {
        $contractRepo = $manager->getRepository(EmploymentContract::class);

        // Check if contract already exists
        $existingContract = $contractRepo->findOneBy(['employee' => $employee->getId()]);

        if ($existingContract) {
            echo "  ✓ Contract already exists for employee {$employee->getId()}\n";
            return;
        }

        $contractConfigs = [
            ['type' => 'Permanent', 'days_month' => 22, 'days_week' => 5, 'hours_day' => 8],
            ['type' => 'Temporary', 'days_month' => 15, 'days_week' => 5, 'hours_day' => 8],
            ['type' => 'Part-Time', 'days_month' => 22, 'days_week' => 5, 'hours_day' => 4],
            ['type' => 'Internship', 'days_month' => 22, 'days_week' => 5, 'hours_day' => 6],
            ['type' => 'Contractor', 'days_month' => 20, 'days_week' => 5, 'hours_day' => 8],
        ];

        // Select random contract config
        $config = $contractConfigs[array_rand($contractConfigs)];

        // Create contract
        $contract = EmploymentContract::create(
            employee: $employee,
            admin: $admin,
            contractType: $contractTypes[$config['type']],
            daysPerMonth: $config['days_month'],
            daysPerWeek: $config['days_week'],
            hoursPerDay: $config['hours_day']
        );

        // Randomly mark some contracts as signed (70% probability)
        if (rand(1, 100) <= 70) {
            $contract->markContractAsSigned();

            // Also sign GDPR and LOPD for signed contracts
            if (rand(1, 100) <= 90) {
                $contract->markGdprAsSigned();
            }
            if (rand(1, 100) <= 90) {
                $contract->markLopdAsSigned();
            }
        }

        $manager->persist($contract);
        $manager->flush();

        echo "  + Created {$config['type']} contract for employee {$employee->getId()}\n";

        // Create workdays
        $this->createWorkdays($manager, $employee, $contract, $config['hours_day']);
    }

    private function createWorkdays(
        ObjectManager $manager,
        Employee $employee,
        EmploymentContract $contract,
        int $hoursPerDay
    ): void {
        $workdayRepo = $manager->getRepository(EmploymentWorkday::class);
        $now = new \DateTimeImmutable();
        $createdCount = 0;
        $skippedCount = 0;

        for ($i = 0; $i < 30; $i++) {
            $date = $now->modify("-{$i} days");

            // Skip weekends
            if ((int)$date->format('N') > 5) {
                continue;
            }

            $startTime = $date->setTime(9, 0);

            // Check if workday already exists
            $existingWorkday = $workdayRepo->findOneBy([
                'employee' => $employee->getId(),
                'startsDate' => $startTime
            ]);

            if ($existingWorkday) {
                $skippedCount++;
                continue;
            }

            // Create workday
            $workday = EmploymentWorkday::create(
                employee: $employee,
                contract: $contract,
                startsDate: $startTime,
                endsDate: $date->setTime(9 + $hoursPerDay, 0),
                createdByUserId: $employee->getUser()->getId()
            );
            $manager->persist($workday);
            $manager->flush();

            // Create realistic clockings
            $this->createClockings($manager, $employee, $workday, $date, $hoursPerDay);

            $createdCount++;
        }

        if ($createdCount > 0) {
            echo "    + Created {$createdCount} workdays for employee {$employee->getId()}\n";
        }
        if ($skippedCount > 0) {
            echo "    ✓ Skipped {$skippedCount} existing workdays\n";
        }
    }

    private function createClockings(
        ObjectManager $manager,
        Employee $employee,
        EmploymentWorkday $workday,
        \DateTimeImmutable $date,
        int $hoursPerDay
    ): void {
        // Clock-in time
        $clockInMinuteOffset = rand(-15, 15);
        $clockInTime = $date->setTime(9, 0)->modify("{$clockInMinuteOffset} minutes");

        $clockIn = EmploymentWorkdayClocking::clockIn(
            workday: $workday,
            employee: $employee,
            clockTime: $clockInTime
        );
        $manager->persist($clockIn);

        // Clock-out time
        $expectedEndHour = 9 + $hoursPerDay;
        $clockOutMinuteOffset = rand(-30, 30);
        $clockOutTime = $date->setTime($expectedEndHour, 0)->modify("{$clockOutMinuteOffset} minutes");

        $clockOut = EmploymentWorkdayClocking::clockOut(
            workday: $workday,
            employee: $employee,
            clockTime: $clockOutTime
        );
        $manager->persist($clockOut);

        // Calculate duration
        $durationMinutes = $clockIn->getDurationInMinutes($clockOut);
        $hours = floor($durationMinutes / 60);
        $minutes = $durationMinutes % 60;

        // ✅ Ensure valid format (always 2 digits for hours and minutes)
        $timeString = sprintf('%02d:%02d:00', $hours, $minutes);

        try {
            $workday->setHoursMadeFromString($timeString);
        } catch (\InvalidArgumentException $e) {
            echo "    ⚠ Warning: Invalid time format '{$timeString}': {$e->getMessage()}\n";
            // Set a default or skip
            $workday->setHoursMadeFromString('00:00:00');
        }

        $manager->persist($workday);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdminFixtures::class,
            EmployeeFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['employment', 'dev'];
    }
}
