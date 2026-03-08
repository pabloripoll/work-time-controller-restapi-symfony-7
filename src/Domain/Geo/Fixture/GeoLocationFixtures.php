<?php

declare(strict_types=1);

namespace App\Domain\Geo\Fixture;

use App\Domain\Geo\Entity\GeoLocation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GeoLocationFixtures extends Fixture implements FixtureGroupInterface
{
    public const CONTINENT_EUROPE = 'geo_continent_europe';
    public const ZONE_EU = 'geo_zone_eu';
    public const COUNTRY_SPAIN = 'geo_country_spain';
    public const REGION_VALENCIA = 'geo_region_valencia';
    public const STATE_VALENCIA = 'geo_state_valencia';

    public function load(ObjectManager $manager): void
    {
        // 1. Europe
        $europe = $this->findOrCreate($manager, 'europe', function() {
            return GeoLocation::createContinent('Europe', 'europe');
        });
        $this->addReference(self::CONTINENT_EUROPE, $europe);

        // 2. European Union
        $eu = $this->findOrCreate($manager, 'european-union', function() use ($europe) {
            return GeoLocation::createZone('European Union', 'european-union', $europe->getId());
        });
        $this->addReference(self::ZONE_EU, $eu);

        // 3. Spain
        $spain = $this->findOrCreate($manager, 'spain', function() use ($europe, $eu) {
            return GeoLocation::createCountry('Spain', 'spain', $europe->getId(), $eu->getId());
        });
        $this->addReference(self::COUNTRY_SPAIN, $spain);

        // 4. Comunitat Valenciana
        $comunitatValenciana = $this->findOrCreate($manager, 'comunitat-valenciana', function() use ($europe, $spain, $eu) {
            return GeoLocation::createRegion(
                'Comunitat Valenciana',
                'comunitat-valenciana',
                $europe->getId(),
                $spain->getId(),
                $eu->getId()
            );
        });
        $this->addReference(self::REGION_VALENCIA, $comunitatValenciana);

        // 5. Valencia
        $valencia = $this->findOrCreate($manager, 'valencia', function() use ($europe, $spain, $comunitatValenciana, $eu) {
            return GeoLocation::createState(
                'Valencia',
                'valencia',
                $europe->getId(),
                $spain->getId(),
                $comunitatValenciana->getId(),
                $eu->getId()
            );
        });
        $this->addReference(self::STATE_VALENCIA, $valencia);

        // 6. Other Spanish regions
        $this->createSpanishRegions($manager, $europe->getId(), $eu->getId(), $spain->getId());

        $manager->flush();
    }

    private function findOrCreate(ObjectManager $manager, string $slug, callable $factory): GeoLocation
    {
        // Check if exists
        $repo = $manager->getRepository(GeoLocation::class);
        $existing = $repo->findOneBy(['slug' => $slug]);

        if ($existing) {
            return $existing;
        }

        // Create new
        $entity = $factory();
        $manager->persist($entity);
        $manager->flush();

        return $entity;
    }

    private function createSpanishRegions(ObjectManager $manager, int $continentId, int $zoneId, int $countryId): void
    {
        $regions = [
            ['name' => 'Catalonia', 'slug' => 'catalonia'],
            ['name' => 'Andalusia', 'slug' => 'andalusia'],
            ['name' => 'Madrid', 'slug' => 'madrid'],
            ['name' => 'Basque Country', 'slug' => 'basque-country'],
        ];

        foreach ($regions as $regionData) {
            $this->findOrCreate($manager, $regionData['slug'], function() use ($regionData, $continentId, $zoneId, $countryId) {
                return GeoLocation::createRegion(
                    $regionData['name'],
                    $regionData['slug'],
                    $continentId,
                    $countryId,
                    $zoneId
                );
            });
        }
    }

    public static function getGroups(): array
    {
        return ['geo', 'dev'];
    }
}
