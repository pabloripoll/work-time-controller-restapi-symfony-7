<?php

declare(strict_types=1);

namespace App\Domain\Employee\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Office\Entity\Department;
use App\Domain\Office\Entity\Job;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employee_workplace')]
class EmployeeWorkplace
{
    private function __construct(
        Employee $employee,
        ?Department $department = null,
        ?Job $job = null
    ) {
        $this->employee = $employee;
        $this->department = $department;
        $this->job = $job;
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

    #[ORM\ManyToOne(targetEntity: Department::class)]
    #[ORM\JoinColumn(name: "department_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Department $department = null;

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function getDepartmentId(): ?int
    {
        return $this->department?->getId();
    }

    #[ORM\ManyToOne(targetEntity: Job::class)]
    #[ORM\JoinColumn(name: "job_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Job $job = null;

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function getJobId(): ?int
    {
        return $this->job?->getId();
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updatedAt;

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Employee $employee,
        ?Department $department = null,
        ?Job $job = null
    ): self {
        return new self($employee, $department, $job);
    }

    public function updateWorkplace(?Department $department, ?Job $job): void
    {
        $this->department = $department;
        $this->job = $job;
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
