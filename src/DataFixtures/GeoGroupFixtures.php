<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GeoGroupFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // This is just a group organizer
    }

    public function getDependencies(): array
    {
        return [
            \App\Domain\Geo\Fixture\GeoLocationFixtures::class, // Changed from GeoLocationFixtures
        ];
    }

    public static function getGroups(): array
    {
        return ['geo'];
    }
}
