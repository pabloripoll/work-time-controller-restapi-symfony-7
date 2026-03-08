<?php

declare(strict_types=1);

namespace App\Application\Employee\DTO;

readonly class EmployeeGeoLocationDTO
{
    public function __construct(
        public int $id,
        public int $employeeId,
        public ?int $continentId = null,
        public ?string $continentName = null,
        public ?int $zoneId = null,
        public ?string $zoneName = null,
        public ?int $countryId = null,
        public ?string $countryName = null,
        public ?int $regionId = null,
        public ?string $regionName = null,
        public ?int $stateId = null,
        public ?string $stateName = null,
        public ?int $districtId = null,
        public ?string $districtName = null,
        public ?int $cityId = null,
        public ?string $cityName = null,
        public ?int $suburbId = null,
        public ?string $suburbName = null,
        public ?string $address = null,
        public ?\DateTimeInterface $createdAt = null,
        public ?\DateTimeInterface $updatedAt = null,
    ) {
    }

    /**
     * Get location hierarchy as string
     */
    public function getLocationHierarchy(): string
    {
        $parts = array_filter([
            $this->continentName,
            $this->zoneName,
            $this->countryName,
            $this->regionName,
            $this->stateName,
            $this->districtName,
            $this->cityName,
            $this->suburbName,
        ]);

        return implode(' > ', $parts);
    }

    /**
     * Get full address with location hierarchy
     */
    public function getFullAddress(): string
    {
        $location = $this->getLocationHierarchy();

        if ($this->address) {
            return $this->address . ($location ? ', ' . $location : '');
        }

        return $location;
    }
}
