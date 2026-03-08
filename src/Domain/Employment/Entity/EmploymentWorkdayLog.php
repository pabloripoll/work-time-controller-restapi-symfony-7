<?php

namespace App\Domain\Employment\Entity;

use App\Domain\Admin\Entity\Admin;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employment_workdays_logs')]
#[ORM\Index(columns: ['created_at'], name: 'idx_employment_workdays_logs_created_at')]
#[ORM\Index(columns: ['action_key'], name: 'idx_employment_workdays_logs_action_key')]
class EmploymentWorkdayLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: EmploymentWorkday::class)]
    #[ORM\JoinColumn(name: 'workday_id', nullable: false, onDelete: 'CASCADE')]
    private EmploymentWorkday $workday;

    public function getWorkday(): EmploymentWorkday
    {
        return $this->workday;
    }

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: 'admin_id', nullable: true, onDelete: 'SET NULL')]
    private ?Admin $admin = null;

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function getAdminId(): ?int
    {
        return $this->admin?->getId();
    }

    #[ORM\Column(type: 'string', length: 128)]
    private string $actionKey;

    public function getActionKey(): string
    {
        return $this->actionKey;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function __construct(
        EmploymentWorkday $workday,
        string $actionKey,
        ?Admin $admin = null
    ) {
        $this->workday = $workday;
        $this->actionKey = $actionKey;
        $this->admin = $admin;
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        EmploymentWorkday $workday,
        string $actionKey,
        ?Admin $admin = null
    ): self {
        return new self($workday, $actionKey, $admin);
    }
}
