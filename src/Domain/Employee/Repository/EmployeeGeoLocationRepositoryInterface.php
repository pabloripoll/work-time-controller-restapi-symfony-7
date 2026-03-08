<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Employee\Entity\EmployeeGeoLocation;

interface EmployeeGeoLocationRepositoryInterface
{
    public function save(EmployeeGeoLocation $GeoLocation): void;

    public function delete(EmployeeGeoLocation $GeoLocation): void;

    public function flush(): void;

    public function findById(int $id): ?EmployeeGeoLocation;

    public function findByEmployeeId(int $employeeId): ?EmployeeGeoLocation;

    public function findByContinentId(int $continentId): array;

    public function findByCountryId(int $countryId): array;

    public function findByRegionId(int $regionId): array;

    public function findByStateId(int $stateId): array;

    public function findByCityId(int $cityId): array;
}
