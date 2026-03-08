<?php

namespace App\Domain\Geo\Repository;

use App\Domain\Geo\Entity\GeoLocation;

interface GeoLocationRepositoryInterface
{
    public function save(GeoLocation $location): void;

    public function findById(int $id): ?GeoLocation;

    public function findBySlug(string $slug): ?GeoLocation;

    public function findByType(string $type): array;

    public function findContinents(): array;

    public function findCountriesByContinent(int $continentId): array;

    public function findRegionsByCountry(int $countryId): array;

    public function findStatesByRegion(int $regionId): array;

    public function findCitiesByState(int $stateId): array;
}