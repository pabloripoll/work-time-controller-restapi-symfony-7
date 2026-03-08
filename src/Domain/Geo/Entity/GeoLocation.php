<?php

namespace App\Domain\Geo\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'geo_locations')]
#[ORM\UniqueConstraint(name: 'uniq_geo_locations_slug', columns: ['slug'])]
#[ORM\UniqueConstraint(name: 'uniq_geo_locations_name', columns: ['name'])]
class GeoLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // Type flags
    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isContinent = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isZone = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isCountry = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isRegion = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isState = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isDistrict = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isCity = false;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isSuburb = false;

    // Parent references
    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $continentId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $zoneId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $countryId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $regionId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $stateId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $districtId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $cityId = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $suburbId = null;

    #[ORM\Column(type: "string", length: 128)]
    private string $slug;

    #[ORM\Column(type: "string", length: 128)]
    private string $name;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;

    // Constructor
    private function __construct(
        string $name,
        string $slug,
        string $type,
        ?int $continentId = null,
        ?int $zoneId = null,
        ?int $countryId = null,
        ?int $regionId = null,
        ?int $stateId = null,
        ?int $districtId = null,
        ?int $cityId = null,
        ?int $suburbId = null
    ) {
        $this->name = $name;
        $this->slug = $slug;
        $this->setType($type);
        $this->continentId = $continentId;
        $this->zoneId = $zoneId;
        $this->countryId = $countryId;
        $this->regionId = $regionId;
        $this->stateId = $stateId;
        $this->districtId = $districtId;
        $this->cityId = $cityId;
        $this->suburbId = $suburbId;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Static factory methods
    public static function createContinent(string $name, string $slug): self
    {
        return new self($name, $slug, 'continent');
    }

    public static function createZone(string $name, string $slug, int $continentId): self
    {
        return new self($name, $slug, 'zone', continentId: $continentId);
    }

    public static function createCountry(string $name, string $slug, int $continentId, ?int $zoneId = null): self
    {
        return new self($name, $slug, 'country', continentId: $continentId, zoneId: $zoneId);
    }

    public static function createRegion(string $name, string $slug, int $continentId, int $countryId, ?int $zoneId = null): self
    {
        return new self($name, $slug, 'region', continentId: $continentId, zoneId: $zoneId, countryId: $countryId);
    }

    public static function createState(
        string $name,
        string $slug,
        int $continentId,
        int $countryId,
        int $regionId,
        ?int $zoneId = null
    ): self {
        return new self(
            $name,
            $slug,
            'state',
            continentId: $continentId,
            zoneId: $zoneId,
            countryId: $countryId,
            regionId: $regionId
        );
    }

    public static function createCity(
        string $name,
        string $slug,
        int $continentId,
        int $countryId,
        int $regionId,
        int $stateId,
        ?int $zoneId = null,
        ?int $districtId = null
    ): self {
        return new self(
            $name,
            $slug,
            'city',
            continentId: $continentId,
            zoneId: $zoneId,
            countryId: $countryId,
            regionId: $regionId,
            stateId: $stateId,
            districtId: $districtId
        );
    }

    // Getters
    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
    public function isContinent(): bool { return $this->isContinent; }
    public function isZone(): bool { return $this->isZone; }
    public function isCountry(): bool { return $this->isCountry; }
    public function isRegion(): bool { return $this->isRegion; }
    public function isState(): bool { return $this->isState; }
    public function isDistrict(): bool { return $this->isDistrict; }
    public function isCity(): bool { return $this->isCity; }
    public function isSuburb(): bool { return $this->isSuburb; }
    public function getContinentId(): ?int { return $this->continentId; }
    public function getZoneId(): ?int { return $this->zoneId; }
    public function getCountryId(): ?int { return $this->countryId; }
    public function getRegionId(): ?int { return $this->regionId; }
    public function getStateId(): ?int { return $this->stateId; }
    public function getDistrictId(): ?int { return $this->districtId; }
    public function getCityId(): ?int { return $this->cityId; }
    public function getSuburbId(): ?int { return $this->suburbId; }

    // Helper method to get type
    public function getType(): string
    {
        if ($this->isContinent) return 'continent';
        if ($this->isZone) return 'zone';
        if ($this->isCountry) return 'country';
        if ($this->isRegion) return 'region';
        if ($this->isState) return 'state';
        if ($this->isDistrict) return 'district';
        if ($this->isCity) return 'city';
        if ($this->isSuburb) return 'suburb';
        return 'unknown';
    }

    // Private helper to set type
    private function setType(string $type): void
    {
        match($type) {
            'continent' => $this->isContinent = true,
            'zone' => $this->isZone = true,
            'country' => $this->isCountry = true,
            'region' => $this->isRegion = true,
            'state' => $this->isState = true,
            'district' => $this->isDistrict = true,
            'city' => $this->isCity = true,
            'suburb' => $this->isSuburb = true,
            default => throw new \InvalidArgumentException("Invalid geo type: {$type}")
        };
    }

    // Lifecycle callbacks
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
