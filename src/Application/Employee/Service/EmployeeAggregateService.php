<?php

declare(strict_types=1);

namespace App\Application\Employee\Service;

use App\Application\Employee\DTO\EmployeeDTO;
use App\Application\Employee\DTO\EmployeeFullDTO;
use App\Application\Employee\DTO\EmployeeProfileDTO;
use App\Application\Employee\DTO\EmployeeContactDTO;
use App\Application\Employee\DTO\EmployeeWorkplaceDTO;
use App\Application\Employee\DTO\EmployeeGeoLocationDTO;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeProfileRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeContactRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeWorkplaceRepositoryInterface;
use App\Domain\Employee\Repository\EmployeeGeoLocationRepositoryInterface;

/**
 * Nested DTOs approach because:
 *
 * Type-safe - Each sub-DTO is strongly typed
 * Clear structure - Easy to see what data comes from where
 * Reusable - Individual DTOs can be used elsewhere
 * Better IDE support - Autocomplete works better with nested objects
 * Easier to test - Mock individual DTOs
 *
 * Use flat DTO only if needing to serialize to a flat JSON structure for API response.
 */
readonly class EmployeeAggregateService
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepo,
        private EmployeeProfileRepositoryInterface $profileRepo,
        private EmployeeContactRepositoryInterface $contactsRepo,
        private EmployeeWorkplaceRepositoryInterface $workplaceRepo,
        private EmployeeGeoLocationRepositoryInterface $geoLocationRepo
    ) {
    }

    /**
     * Get complete employee data (nested DTOs version)
     */
    public function getFullEmployee(int $employeeId): ?EmployeeFullDTO
    {
        $employee = $this->employeeRepo->findById($employeeId);
        if (! $employee) {
            return null;
        }

        $employeeDTO = new EmployeeDTO(
            id: $employee->getId(),
            uuid: (string) $employee->getUuid(),
            userId: $employee->getUser()->getId(),
            email: (string) $employee->getUser()->getEmail(),
            isActive: $employee->isActive(),
            isBanned: $employee->isBanned(),
            createdAt: $employee->getCreatedAt(),
            updatedAt: $employee->getUpdatedAt()
        );

        // Profile
        $profile = $this->profileRepo->findByEmployeeId($employeeId);
        $profileDTO = $profile ? new EmployeeProfileDTO(
            id: $profile->getId(),
            employeeId: $profile->getEmployeeId(),
            name: $profile->getName(),
            surname: $profile->getSurname(),
            fullName: $profile->getFullName(),
            birthdate: $profile->getBirthdate()
        ) : null;

        // Contacts
        $contacts = $this->contactsRepo->findByEmployeeId($employeeId);
        $contactsDTO = $contacts ? new EmployeeContactDTO(
            id: $contacts->getId(),
            employeeId: $contacts->getEmployeeId(),
            postal: $contacts->getPostal(),
            email: $contacts->getEmail(),
            phone: $contacts->getPhone(),
            mobile: $contacts->getMobile()
        ) : null;

        // Workplace
        $workplace = $this->workplaceRepo->findByEmployeeId($employeeId);
        $workplaceDTO = $workplace ? new EmployeeWorkplaceDTO(
            id: $workplace->getId(),
            employeeId: $workplace->getEmployeeId(),
            departmentId: $workplace->getDepartmentId(),
            departmentName: $workplace->getDepartment()?->getName(),
            jobId: $workplace->getJobId(),
            jobTitle: $workplace->getJob()?->getTitle(),
            createdAt: $workplace->getCreatedAt(),
            updatedAt: $workplace->getUpdatedAt()
        ) : null;

        // Geo Location
        $geoLocation = $this->geoLocationRepo->findByEmployeeId($employeeId);
        $geoLocationDTO = $geoLocation ? new EmployeeGeoLocationDTO(
            id: $geoLocation->getId(),
            employeeId: $geoLocation->getEmployeeId(),
            continentId: $geoLocation->getContinent()?->getId(),
            continentName: $geoLocation->getContinent()?->getName(),
            zoneId: $geoLocation->getZone()?->getId(),
            zoneName: $geoLocation->getZone()?->getName(),
            countryId: $geoLocation->getCountry()?->getId(),
            countryName: $geoLocation->getCountry()?->getName(),
            regionId: $geoLocation->getRegion()?->getId(),
            regionName: $geoLocation->getRegion()?->getName(),
            stateId: $geoLocation->getState()?->getId(),
            stateName: $geoLocation->getState()?->getName(),
            districtId: $geoLocation->getDistrict()?->getId(),
            districtName: $geoLocation->getDistrict()?->getName(),
            cityId: $geoLocation->getCity()?->getId(),
            cityName: $geoLocation->getCity()?->getName(),
            suburbId: $geoLocation->getSuburb()?->getId(),
            suburbName: $geoLocation->getSuburb()?->getName(),
            address: $geoLocation->getAddress(),
            createdAt: $geoLocation->getCreatedAt(),
            updatedAt: $geoLocation->getUpdatedAt()
        ) : null;

        return new EmployeeFullDTO(
            employee: $employeeDTO,
            profile: $profileDTO,
            contacts: $contactsDTO,
            workplace: $workplaceDTO,
            geoLocation: $geoLocationDTO
        );
    }
}
