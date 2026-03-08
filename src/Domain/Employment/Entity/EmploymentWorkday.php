<?php

declare(strict_types=1);

namespace App\Domain\Employment\Entity;

use App\Domain\Employee\Entity\Employee;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employment_workdays')]
#[ORM\Index(columns: ['starts_date'], name: 'idx_employment_workdays_start_date')]
#[ORM\Index(columns: ['deleted_at'], name: 'idx_employment_workdays_deleted_at')]
class EmploymentWorkday
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['workday:read', 'workday:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: EmploymentContract::class, inversedBy: 'workdays')]
    #[ORM\JoinColumn(name: 'contract_id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['workday:read'])]
    private EmploymentContract $contract;

    public function getContract(): EmploymentContract
    {
        return $this->contract;
    }

    public function getContractId(): int
    {
        return $this->contract->getId();
    }

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: 'employee_id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['workday:read'])]
    private Employee $employee;

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getEmployeeId(): int
    {
        return $this->employee->getId();
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['workday:read', 'workday:list'])]
    private ?\DateTimeImmutable $startsDate = null;

    public function getStartsDate(): ?\DateTimeImmutable
    {
        return $this->startsDate;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['workday:read', 'workday:list'])]
    private ?\DateTimeImmutable $endsDate = null;

    public function getEndsDate(): ?\DateTimeImmutable
    {
        return $this->endsDate;
    }

    #[ORM\Column(type: 'time', nullable: true)]
    #[Groups(['workday:read', 'workday:list'])]
    private ?\DateTimeInterface $hoursExtra = null;

    public function getHoursExtra(): ?\DateTimeInterface
    {
        return $this->hoursExtra;
    }

    public function getHoursExtraFormatted(): ?string
    {
        return $this->hoursExtra?->format('H:i:s');
    }

    public function getHoursExtraInMinutes(): ?int
    {
        if (! $this->hoursExtra) {
            return null;
        }

        return ((int) $this->hoursExtra->format('H')) * 60
             + ((int) $this->hoursExtra->format('i'));
    }

    public function setHoursExtra(?\DateTimeInterface $hoursExtra): self
    {
        $this->hoursExtra = $hoursExtra;
        return $this;
    }

    #[ORM\Column(type: 'time', nullable: true)]
    #[Groups(['workday:read', 'workday:list'])]
    private ?\DateTimeInterface $hoursMade = null;

    public function getHoursMade(): ?\DateTimeInterface
    {
        return $this->hoursMade;
    }

    public function getHoursMadeFormatted(): ?string
    {
        return $this->hoursMade?->format('H:i:s');
    }

    public function getHoursMadeInMinutes(): ?int
    {
        if (! $this->hoursMade) {
            return null;
        }

        return ((int) $this->hoursMade->format('H')) * 60
             + ((int) $this->hoursMade->format('i'));
    }

    public function setHoursMade(?\DateTimeInterface $hoursMade): self
    {
        $this->hoursMade = $hoursMade;
        return $this;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['workday:read'])]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['workday:read'])]
    private \DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['workday:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    #[ORM\OneToMany(mappedBy: 'workday', targetEntity: EmploymentWorkdayClocking::class)]
    private Collection $clockings;

    private function __construct(
        EmploymentContract $contract,
        Employee $employee,
        ?\DateTimeImmutable $startsDate,
        ?\DateTimeImmutable $endsDate,
        int $createdByUserId
    ) {
        $this->contract = $contract;
        $this->employee = $employee;
        $this->startsDate = $startsDate;
        $this->endsDate = $endsDate;
        $this->clockings = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Employee $employee,
        EmploymentContract $contract,
        ?\DateTimeImmutable $startsDate,
        ?\DateTimeImmutable $endsDate,
        int $createdByUserId
    ): self {
        return new self($contract, $employee, $startsDate, $endsDate, $createdByUserId);
    }

    public function getClockings(): Collection
    {
        return $this->clockings;
    }

    public function updateTimes(
        \DateTimeImmutable $startsDate,
        \DateTimeImmutable $endsDate,
    ): void {
        $this->startsDate = $startsDate;
        $this->endsDate = $endsDate;
        $this->markAsUpdated();
    }

    /**
     * Set hours extra from string format "HH:MM" or "HH:MM:SS"
     */
    public function setHoursExtraFromString(string $time): self
    {
        // ✅ Fix: Check for false before assigning
        $dateTime = \DateTime::createFromFormat('H:i:s', $time);

        if ($dateTime === false) {
            $dateTime = \DateTime::createFromFormat('H:i', $time);
        }

        if ($dateTime === false) {
            throw new \InvalidArgumentException("Invalid time format: {$time}. Expected HH:MM or HH:MM:SS");
        }

        $this->hoursExtra = $dateTime;
        return $this;
    }

    /**
     * Set hours made from string format "HH:MM" or "HH:MM:SS"
     */
    public function setHoursMadeFromString(string $time): self
    {
        // ✅ Fix: Check for false before assigning
        $dateTime = \DateTime::createFromFormat('H:i:s', $time);

        if ($dateTime === false) {
            $dateTime = \DateTime::createFromFormat('H:i', $time);
        }

        if ($dateTime === false) {
            throw new \InvalidArgumentException("Invalid time format: {$time}. Expected HH:MM or HH:MM:SS");
        }

        $this->hoursMade = $dateTime;
        $this->markAsUpdated();
        return $this;
    }

    /**
     * Calculate total work duration in hours
     */
    public function calculateWorkDuration(): ?float
    {
        if (! $this->startsDate || !$this->endsDate) {
            return null;
        }

        $diff = $this->endsDate->getTimestamp() - $this->startsDate->getTimestamp();
        return $diff / 3600; // Convert seconds to hours
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

    private function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * HOOKS
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
