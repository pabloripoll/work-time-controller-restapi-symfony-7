<?php

namespace App\Domain\Employment\Entity;

use App\Domain\Admin\Entity\Admin;
use App\Domain\Employee\Entity\Employee;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employment_contracts')]
class EmploymentContract
{
    private function __construct(
        EmploymentContractType $contractType,
        Employee $employee,
        Admin $admin,
        int $daysPerMonth,
        int $daysPerWeek,
        int $hoursPerDay
    ) {
        $this->contractType = $contractType;
        $this->employee = $employee;
        $this->admin = $admin;
        $this->daysPerMonth = $daysPerMonth;
        $this->daysPerWeek = $daysPerWeek;
        $this->hoursPerDay = $hoursPerDay;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['contract:read', 'contract:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: EmploymentContractType::class, inversedBy: 'contracts')]
    #[ORM\JoinColumn(name: 'contract_type_id', nullable: false)]
    #[Groups(['contract:read'])]
    private EmploymentContractType $contractType;

    public function getContractType(): EmploymentContractType
    {
        return $this->contractType;
    }

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: 'employee_id', nullable: false)]
    #[Groups(['contract:read'])]
    private Employee $employee;

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: 'admin_id', nullable: false)]
    #[Groups(['contract:read'])]
    private Admin $admin;

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    #[ORM\Column(type: 'integer')]
    #[Groups(['contract:read', 'contract:list'])]
    private int $daysPerMonth;

    public function getDaysPerMonth(): int
    {
        return $this->daysPerMonth;
    }

    #[ORM\Column(type: 'integer')]
    #[Groups(['contract:read', 'contract:list'])]
    private int $daysPerWeek;

    public function getDaysPerWeek(): int
    {
        return $this->daysPerWeek;
    }

    #[ORM\Column(type: 'integer')]
    #[Groups(['contract:read', 'contract:list'])]
    private int $hoursPerDay;

    public function getHoursPerDay(): int
    {
        return $this->hoursPerDay;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $hasContractSigned = false;

    public function getHasContractSigned(): bool
    {
        return $this->hasContractSigned;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $hasGrpdSigned = false;

    public function getHasGrpdSigned(): bool
    {
        return $this->hasGrpdSigned;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $hasLopdSigned = false;

    public function getHasLopdSigned(): bool
    {
        return $this->hasLopdSigned;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['contract:read'])]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['contract:read'])]
    private \DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['contract:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    #[ORM\OneToMany(mappedBy: 'contract', targetEntity: EmploymentWorkday::class)]
    private Collection $workdays;

    /**
     * BUSINESS METHODS
     */

    public static function create(
        EmploymentContractType $contractType,
        Employee $employee,
        Admin $admin,
        int $daysPerMonth,
        int $daysPerWeek,
        int $hoursPerDay
    ): self {
        return new self($contractType, $employee, $admin, $daysPerMonth, $daysPerWeek, $hoursPerDay);
    }

    public function getWorkdays(): Collection
    {
        return $this->workdays;
    }

    public function updateWorkSchedule(
        int $daysPerMonth,
        int $daysPerWeek,
        int $hoursPerDay
    ): void {
        $this->daysPerMonth = $daysPerMonth;
        $this->daysPerWeek = $daysPerWeek;
        $this->hoursPerDay = $hoursPerDay;
        $this->markAsUpdated();
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->markAsUpdated();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
        $this->markAsUpdated();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function updateTerms(
        int $daysPerMonth,
        int $daysPerWeek,
        int $hoursPerDay
    ): void {
        $this->daysPerMonth = $daysPerMonth;
        $this->daysPerWeek = $daysPerWeek;
        $this->hoursPerDay = $hoursPerDay;
        $this->markAsUpdated();
    }

    public function markContractAsSigned(): void
    {
        $this->hasContractSigned = true;
        $this->markAsUpdated();
    }

    public function markGdprAsSigned(): void
    {
        $this->hasGrpdSigned = true;
        $this->markAsUpdated();
    }

    public function markLopdAsSigned(): void
    {
        $this->hasLopdSigned = true;
        $this->markAsUpdated();
    }

    private function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     *  HOOKS
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
