<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Admin\Repository\AdminRepositoryInterface;
use App\Domain\Employee\Entity\Employee;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Master\Entity\Master;
use App\Domain\Master\Repository\MasterRepositoryInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    /**
     * Get authenticated user with proper type
     */
    protected function getAuthenticatedUser(): User
    {
        $user = $this->getUser();

        if (! $user instanceof User) {
            throw new \LogicException('User must be an instance of ' . User::class);
        }

        return $user;
    }

    /**
     * Get authenticated user's Employee entity
     */
    protected function getAuthenticatedEmployee(EmployeeRepositoryInterface $employeeRepo): Employee
    {
        $user = $this->getAuthenticatedUser();

        if (! $user->isEmployee()) {
            throw new \LogicException('User is not an employee');
        }

        $employee = $employeeRepo->findByUserId($user->getId());

        if (! $employee) {
            throw new \LogicException('Employee record not found for user ID: ' . $user->getId());
        }

        return $employee;
    }

    /**
     * Get authenticated user's Admin entity
     */
    protected function getAuthenticatedAdmin(AdminRepositoryInterface $adminRepo): Admin
    {
        $user = $this->getAuthenticatedUser();

        if (! $user->isAdmin()) {
            throw new \LogicException('User is not an admin');
        }

        $admin = $adminRepo->findByUserId($user->getId());

        if (! $admin) {
            throw new \LogicException('Admin record not found for user ID: ' . $user->getId());
        }

        return $admin;
    }

    /**
     * Get authenticated user's Master entity
     */
    protected function getAuthenticatedMaster(MasterRepositoryInterface $masterRepo): Master
    {
        $user = $this->getAuthenticatedUser();

        if (! $user->isMaster()) {
            throw new \LogicException('User is not a master');
        }

        $master = $masterRepo->findByUserId($user->getId());

        if (! $master) {
            throw new \LogicException('Master record not found for user ID: ' . $user->getId());
        }

        return $master;
    }
}
