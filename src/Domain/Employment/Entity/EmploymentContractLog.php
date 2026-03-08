<?php

namespace App\Domain\Employment\Entity;

use App\Domain\Admin\Entity\Admin;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employment_contracts_logs')]
#[ORM\Index(columns: ['created_at'], name: 'idx_employment_contracts_logs_created_at')]
#[ORM\Index(columns: ['action_key'], name: 'idx_employment_contracts_logs_action_key')]
class EmploymentContractLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: EmploymentContract::class)]
    #[ORM\JoinColumn(name: 'contract_id', nullable: true, onDelete: 'CASCADE')]
    private ?EmploymentContract $contract = null;

    public function getContract(): ?EmploymentContract
    {
        return $this->contract;
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
        ?EmploymentContract $contract,
        string $actionKey,
        ?Admin $admin = null
    ) {
        $this->contract = $contract;
        $this->actionKey = $actionKey;
        $this->admin = $admin;
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        EmploymentContract $contract,
        string $actionKey,
        ?Admin $admin = null
    ): self {
        return new self($contract, $actionKey, $admin);
    }

    /**
     * Log action without contract reference (for system-level actions)
     */
    public static function createSystemLog(
        string $actionKey,
        ?Admin $admin = null
    ): self {
        return new self(null, $actionKey, $admin);
    }
}
