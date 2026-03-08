<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class EmploymentGroupFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // intentionally empty — dependencies will be executed before this fixture
    }

    public function getDependencies(): array
    {
        return [
            \App\Domain\Employment\Fixture\EmploymentFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['employment'];
    }
}
