<?php

namespace App\Application\Employee\Query;

use App\Application\Employee\DTO\EmployeeDTO;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetEmployeeByUserIdHandler
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function __invoke(GetEmployeeByUserIdQuery $query): EmployeeDTO
    {
        $employee = $this->employeeRepository->findByUserId($query->userId);

        if (! $employee) {
            throw new EntityNotFoundException("Employee for user ID {$query->userId} not found");
        }

        return new EmployeeDTO(
            id: $employee->getId(),
            userId: $employee->getUser()->getId(),
            uuid: $employee->getUuid(),
            geoCountryId: $employee->getGeoCountry()?->getId(),
            geoRegionId: $employee->getGeoRegion()?->getId(),
            geoStateId: $employee->getGeoState()?->getId(),
            geoDistrictId: $employee->getGeoDistrict()?->getId(),
            geoCityId: $employee->getGeoCity()?->getId(),
            isActive: $employee->isActive(),
            isBanned: $employee->isBanned(),
            createdAt: $employee->getCreatedAt(),
            updatedAt: $employee->getUpdatedAt()
        );
    }
}
