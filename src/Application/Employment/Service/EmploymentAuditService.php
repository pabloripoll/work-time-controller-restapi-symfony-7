<?php

namespace App\Application\Employment\Service;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Employment\Entity\EmploymentContract;
use App\Domain\Employment\Entity\EmploymentContractLog;
use App\Domain\Employment\Entity\EmploymentWorkday;
use App\Domain\Employment\Entity\EmploymentWorkdayLog;
use App\Domain\Employment\Entity\EmploymentWorkdayClocking;
use App\Domain\Employment\Entity\EmploymentWorkdayClockingLog;
use App\Domain\Employment\ValueObject\EmploymentActionKey;
use Doctrine\ORM\EntityManagerInterface;

readonly class EmploymentAuditService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Log contract action
     */
    public function logContractAction(
        EmploymentContract $contract,
        EmploymentActionKey $actionKey,
        ?Admin $admin = null
    ): void {
        $log = EmploymentContractLog::create(
            contract: $contract,
            actionKey: $actionKey->value,
            admin: $admin
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * Log workday action
     */
    public function logWorkdayAction(
        EmploymentWorkday $workday,
        EmploymentActionKey $actionKey,
        ?Admin $admin = null
    ): void {
        $log = EmploymentWorkdayLog::create(
            workday: $workday,
            actionKey: $actionKey->value,
            admin: $admin
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * Log clocking action
     */
    public function logClockingAction(
        EmploymentWorkdayClocking $clocking,
        EmploymentActionKey $actionKey,
        ?Admin $admin = null
    ): void {
        $log = EmploymentWorkdayClockingLog::create(
            clocking: $clocking,
            actionKey: $actionKey->value,
            admin: $admin
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * Get contract audit trail
     */
    public function getContractAuditTrail(int $contractId): array
    {
        return $this->entityManager
            ->getRepository(EmploymentContractLog::class)
            ->findBy(
                ['contract' => $contractId],
                ['createdAt' => 'DESC']
            );
    }

    /**
     * Get workday audit trail
     */
    public function getWorkdayAuditTrail(int $workdayId): array
    {
        return $this->entityManager
            ->getRepository(EmploymentWorkdayLog::class)
            ->findBy(
                ['workday' => $workdayId],
                ['createdAt' => 'DESC']
            );
    }

    /**
     * Get clocking audit trail
     */
    public function getClockingAuditTrail(int $clockingId): array
    {
        return $this->entityManager
            ->getRepository(EmploymentWorkdayClockingLog::class)
            ->findBy(
                ['clocking' => $clockingId],
                ['createdAt' => 'DESC']
            );
    }
}
