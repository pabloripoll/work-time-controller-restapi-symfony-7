<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Employee\Entity\Employee;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "admins")]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_active = false;

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_banned = false;

    public function getIsBanned(): bool
    {
        return $this->is_banned;
    }

    public function setIsBanned(bool $is_banned): self
    {
        $this->is_banned = $is_banned;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_superadmin = false;

    public function getIsSuperadmin(): bool
    {
        return $this->is_superadmin;
    }

    public function setIsSuperadmin(bool $is_superadmin): self
    {
        $this->is_superadmin = $is_superadmin;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: "employee_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Employee $employee = null;

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employee?->getId();
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updated_at;

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * BUSINESS METHODS
     */

    public function isSuperadmin(): bool
    {
        return $this->is_superadmin;
    }

    public function makeSuperadmin(): self
    {
        $this->is_superadmin = true;
        return $this;
    }

    public function revokeSuperadmin(): self
    {
        $this->is_superadmin = false;
        return $this;
    }

    public function linkEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function unlinkEmployee(): self
    {
        $this->employee = null;
        return $this;
    }

    public function hasEmployee(): bool
    {
        return $this->employee !== null;
    }

    /**
     *  HOOKS
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void {
        $this->created_at = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updated_at = new \DateTime();
    }
}
