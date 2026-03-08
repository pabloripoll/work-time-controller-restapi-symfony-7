<?php

declare(strict_types=1);

namespace App\Domain\Employee\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Geo\Entity\GeoLocation;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employee_geo_location')]
class EmployeeGeoLocation
{
    private function __construct(
        Employee $employee,
        ?string $address = null
    ) {
        $this->employee = $employee;
        $this->address = $address;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: "employee_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Employee $employee;

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getEmployeeId(): int
    {
        return $this->employee->getId();
    }

    // All geo location fields reference the same GeoLocation table
    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "continent_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $continent = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "zone_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $zone = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "country_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $country = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "region_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $region = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "state_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $state = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "district_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $district = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "city_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $city = null;

    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    #[ORM\JoinColumn(name: "suburb_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?GeoLocation $suburb = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;

    // Getters
    public function getContinent(): ?GeoLocation { return $this->continent; }
    public function getZone(): ?GeoLocation { return $this->zone; }
    public function getCountry(): ?GeoLocation { return $this->country; }
    public function getRegion(): ?GeoLocation { return $this->region; }
    public function getState(): ?GeoLocation { return $this->state; }
    public function getDistrict(): ?GeoLocation { return $this->district; }
    public function getCity(): ?GeoLocation { return $this->city; }
    public function getSuburb(): ?GeoLocation { return $this->suburb; }
    public function getAddress(): ?string { return $this->address; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeInterface { return $this->updatedAt; }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Employee $employee,
        ?string $address = null
    ): self {
        return new self($employee, $address);
    }

    public function setLocation(
        ?GeoLocation $continent = null,
        ?GeoLocation $zone = null,
        ?GeoLocation $country = null,
        ?GeoLocation $region = null,
        ?GeoLocation $state = null,
        ?GeoLocation $district = null,
        ?GeoLocation $city = null,
        ?GeoLocation $suburb = null,
        ?string $address = null
    ): void {
        $this->continent = $continent;
        $this->zone = $zone;
        $this->country = $country;
        $this->region = $region;
        $this->state = $state;
        $this->district = $district;
        $this->city = $city;
        $this->suburb = $suburb;
        $this->address = $address;
        $this->updatedAt = new \DateTime();
    }

    /**
     * HOOKS
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void
    {
        $this->createdAt = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
