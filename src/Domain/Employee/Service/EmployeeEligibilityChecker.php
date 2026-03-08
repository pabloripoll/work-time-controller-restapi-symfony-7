<?php

declare(strict_types=1);

namespace App\Domain\Employee\Service;

use App\Domain\User\Entity\User;
use App\Domain\Employee\Entity\Employee;

// This is a Domain Service (no infrastructure)
class EmployeeEligibilityChecker
{
    /**
     * Pure business logic - no repository, no hashing
     */
    public function isEligibleForLogin(User $user, Employee $employee): bool
    {
        // Business rules only
        if (! $user->isEmployee()) {
            return false;
        }

        if (! $employee->isActive()) {
            return false;
        }

        if ($employee->isBanned()) {
            return false;
        }

        return true;
    }
}
