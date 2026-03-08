<?php

namespace App\Application\Geo\DTO;

class GeoLocationDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $countryId,
        public readonly int $regionId,
        public readonly int $stateId,
        public readonly int $districtId,
        public readonly string $countryName,
        public readonly string $regionName,
        public readonly string $stateName,
        public readonly string $districtName,
        public readonly string $name,
        public readonly \DateTimeInterface $createdAt,
        public readonly \DateTimeInterface $updatedAt
    ) {
    }
}
