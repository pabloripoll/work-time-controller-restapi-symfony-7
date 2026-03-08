<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UsersGroupFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // This is just a group organizer
    }

    public function getDependencies(): array
    {
        return [
            \App\Domain\Master\Fixture\MasterFixtures::class,
            \App\Domain\Employee\Fixture\EmployeeFixtures::class,
            \App\Domain\Admin\Fixture\AdminFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}
