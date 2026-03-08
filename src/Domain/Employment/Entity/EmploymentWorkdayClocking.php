<?php

namespace App\Domain\Employment\Entity;

use App\Domain\Employee\Entity\Employee;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employment_workday_clockings')]
class EmploymentWorkdayClocking
{
    private function __construct(
        EmploymentWorkday $workday,
        Employee $employee,
        bool $clockIn,
        bool $clockOut,
        ?\DateTimeImmutable $clockTime = null
    ) {
        $this->workday = $workday;
        $this->employee = $employee;
        $this->clockIn = $clockIn;
        $this->clockOut = $clockOut;
        $this->createdAt = $clockTime ?? new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['clocking:read', 'clocking:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: 'employee_id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['clocking:read'])]
    private Employee $employee;

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getEmployeeId(): int
    {
        return $this->employee->getId();
    }

    #[ORM\ManyToOne(targetEntity: EmploymentWorkday::class, inversedBy: 'clockings')]
    #[ORM\JoinColumn(name: 'workday_id', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['clocking:read'])]
    private EmploymentWorkday $workday;

    public function getWorkday(): EmploymentWorkday
    {
        return $this->workday;
    }

    public function getWorkdayId(): int
    {
        return $this->workday->getId();
    }

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Groups(['clocking:read', 'clocking:list'])]
    private bool $clockIn = false;

    public function isClockIn(): bool
    {
        return $this->clockIn;
    }

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Groups(['clocking:read', 'clocking:list'])]
    private bool $clockOut = false;

    public function isClockOut(): bool
    {
        return $this->clockOut;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['clocking:read', 'clocking:list'])]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the actual clock time (alias for createdAt for clarity)
     */
    public function getClockTime(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['clocking:read'])]
    private \DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['clocking:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * BUSINESS METHODS - Factory Methods
     */

    /**
     * Create a clock-in record
     */
    public static function clockIn(
        EmploymentWorkday $workday,
        Employee $employee,
        ?\DateTimeImmutable $clockTime = null
    ): self {
        return new self(
            workday: $workday,
            employee: $employee,
            clockIn: true,
            clockOut: false,
            clockTime: $clockTime
        );
    }

    /**
     * Create a clock-out record
     */
    public static function clockOut(
        EmploymentWorkday $workday,
        Employee $employee,
        ?\DateTimeImmutable $clockTime = null
    ): self {
        return new self(
            workday: $workday,
            employee: $employee,
            clockIn: false,
            clockOut: true,
            clockTime: $clockTime
        );
    }

    /**
     * BUSINESS METHODS - Actions
     */

    /**
     * Correct the clock time (admin only)
     */
    public function correctTime(\DateTimeImmutable $correctedTime): void
    {
        $this->createdAt = $correctedTime;
        $this->markAsUpdated();
    }

    /**
     * Soft delete the clocking record
     */
    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->markAsUpdated();
    }

    /**
     * Restore a soft-deleted record
     */
    public function restore(): void
    {
        $this->deletedAt = null;
        $this->markAsUpdated();
    }

    /**
     * Get duration from this clock-in to a given clock-out (in minutes)
     */
    public function getDurationInMinutes(self $clockOut): int
    {
        if (! $this->isClockIn() || !$clockOut->isClockOut()) {
            throw new \LogicException('Duration can only be calculated from clock-in to clock-out');
        }

        $diff = $clockOut->getCreatedAt()->getTimestamp() - $this->createdAt->getTimestamp();
        return (int) ($diff / 60);
    }

    /**
     * Check if this is the same type as another clocking
     */
    public function isSameTypeAs(self $other): bool
    {
        return ($this->clockIn === $other->clockIn) && ($this->clockOut === $other->clockOut);
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
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
