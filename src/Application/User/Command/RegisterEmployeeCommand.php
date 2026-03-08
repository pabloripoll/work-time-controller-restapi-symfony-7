<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\Office\Entity\Department;
use App\Domain\Office\Entity\Job;
use App\Domain\Geo\Entity\GeoCountry;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Geo\Entity\GeoState;
use App\Domain\Geo\Entity\GeoDistrict;
use App\Domain\Geo\Entity\GeoCity;

final readonly class RegisterEmployeeCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        public string $surname,
        public int $createdByUserId,
        public ?string $birthDate = null,
        public ?string $emailSecondary = null,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?Department $department = null,
        public ?Job $job = null,
        public ?GeoCountry $geoCountry = null,
        public ?GeoRegion $geoRegion = null,
        public ?GeoState $geoState = null,
        public ?GeoDistrict $geoDistrict = null,
        public ?GeoCity $geoCity = null,
    ) {}
}
