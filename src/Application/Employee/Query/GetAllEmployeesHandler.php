<?php

namespace App\Application\Employee\Query;

use App\Application\Employee\DTO\EmployeeDTO;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAllEmployeesHandler
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function __invoke(GetAllEmployeesQuery $query): array
    {
        $employees = $this->employeeRepository->findAllActive();

        return array_map(
            fn($employee) => new EmployeeDTO(
                id: $employee->getId(),
                uuid: $employee->getUuid(),
                userId: $employee->getUser()->getId(),
                geoCountryId: $employee->getGeoCountry()?->getId(),
                geoRegionId: $employee->getGeoRegion()?->getId(),
                geoStateId: $employee->getGeoState()?->getId(),
                geoDistrictId: $employee->getGeoDistrict()?->getId(),
                geoCityId: $employee->getGeoCity()?->getId(),
                isActive: $employee->isActive(),
                isBanned: $employee->isBanned(),
                createdAt: $employee->getCreatedAt(),
                updatedAt: $employee->getUpdatedAt()
            ),
            $employees
        );
    }
}
