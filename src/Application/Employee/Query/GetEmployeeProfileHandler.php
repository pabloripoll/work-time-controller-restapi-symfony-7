<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

use App\Application\Employee\DTO\EmployeeProfileDTO;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeProfileRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;

final readonly class GetEmployeeProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeRepositoryInterface $employeeRepository,
        private EmployeeProfileRepositoryInterface $employeProfileRepository
    ) {}

    public function __invoke(GetEmployeeProfileQuery $query): EmployeeProfileDTO
    {
        $userId = $query->userId;

        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            throw new EntityNotFoundException('User not found');
        }

        $employee = $this->employeeRepository->findByUserId($userId);
        $employeeProfile = $this->employeProfileRepository->findByUserId($userId);

        if ($employeeProfile === null) {
            throw new EntityNotFoundException('Employee profile not found');
        }

        return new EmployeeProfileDTO(
            userId: $user->getId(),
            uuid: (string)$employee->getUuid(),
            email: (string)$user->getEmail(),
            name: $employeeProfile->getName(),
            surname: $employeeProfile->getSurname(),
            phone: $employeeProfile->getPhone(),
            mobile: $employeeProfile->getPhone(),
            birthDate: $employeeProfile->getBirthDate()?->format('Y-m-d'),
            createdAt: $user->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }
}
