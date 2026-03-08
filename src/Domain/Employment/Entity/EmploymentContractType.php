<?php

namespace App\Domain\Employment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'employment_contract_types')]
#[ORM\Index(columns: ['title'], name: 'idx_employment_contract_types_title')]
class EmploymentContractType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['contract_type:read', 'contract_type:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    #[Groups(['contract_type:read', 'contract_type:list'])]
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['contract_type:read'])]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['contract_type:read'])]
    private \DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['contract_type:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    #[ORM\OneToMany(mappedBy: 'contractType', targetEntity: EmploymentContract::class)]
    private Collection $contracts;

    private function __construct(string $title)
    {
        $this->title = $title;
        $this->contracts = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * BUSINESS METHODS
     */
    public static function create(string $title): self
    {
        return new self($title);
    }

    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function updateTitle(string $title): void
    {
        $this->title = $title;
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
