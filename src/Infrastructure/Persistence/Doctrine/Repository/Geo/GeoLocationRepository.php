<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Geo;

use App\Domain\Geo\Entity\GeoLocation;
use App\Domain\Geo\Repository\GeoLocationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GeoLocationRepository extends ServiceEntityRepository implements GeoLocationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoLocation::class);
    }

    public function save(GeoLocation $location): void
    {
        $this->getEntityManager()->persist($location);
        $this->getEntityManager()->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function findById(int $id): ?GeoLocation
    {
        return $this->find($id);
    }

    public function findBySlug(string $slug): ?GeoLocation
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function findByType(string $type): array
    {
        $field = 'is' . ucfirst($type);
        return $this->findBy([$field => true], ['name' => 'ASC']);
    }

    public function findContinents(): array
    {
        return $this->findBy(['isContinent' => true], ['name' => 'ASC']);
    }

    public function findCountriesByContinent(int $continentId): array
    {
        return $this->findBy([
            'isCountry' => true,
            'continentId' => $continentId
        ], ['name' => 'ASC']);
    }

    public function findRegionsByCountry(int $countryId): array
    {
        return $this->findBy([
            'isRegion' => true,
            'countryId' => $countryId
        ], ['name' => 'ASC']);
    }

    public function findStatesByRegion(int $regionId): array
    {
        return $this->findBy([
            'isState' => true,
            'regionId' => $regionId
        ], ['name' => 'ASC']);
    }

    public function findCitiesByState(int $stateId): array
    {
        return $this->findBy([
            'isCity' => true,
            'stateId' => $stateId
        ], ['name' => 'ASC']);
    }
}
