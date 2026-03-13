<?php

namespace App\Infrastructure\Http;

use App\Domain\User\Entity\User;
use App\Domain\Employee\Entity\Employee;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * This script is not being used - It is for further usage between user and employee entities on auth
 * ./config/services.yaml
 * services:
 *   App\Infrastructure\Http\AuthenticatedEmployeeResolver:
 *      tags:
 *          - { name: controller.argument_value_resolver, priority: 150 }
 *
 * public function someController(Employee $employee, ClockInHandler $handler): JsonResponse {} // Is automatically injected!
 * $employee->getId() can be used with any extra query
 */
readonly class AuthenticatedEmployeeResolver implements ValueResolverInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private EmployeeRepositoryInterface $employeeRepo
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Only resolve if the argument type is Employee
        if ($argument->getType() !== Employee::class) {
            return [];
        }

        $token = $this->tokenStorage->getToken();
        if (! $token) {
            return [];
        }

        $user = $token->getUser();
        if (! $user instanceof User) {
            return [];
        }

        $employee = $this->employeeRepo->findByUserId($user->getId());
        if (! $employee) {
            throw new \RuntimeException('Employee not found for authenticated user');
        }

        yield $employee;
    }
}
