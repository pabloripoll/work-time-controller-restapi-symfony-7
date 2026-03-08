<?php

namespace App\Domain\Office\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "office_departments")]
#[ORM\UniqueConstraint(name: "uniq_office_departments_name", columns: ["name"])]
class Department
{
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['department:read', 'department:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * One geographic country has many geographic jobs.
     * mappedBy refers to the property name on Department (see Department::$department).
     */
    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Department::class, cascade: ['persist'], orphanRemoval: false)]
    private Collection $jobs;

    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    #[ORM\Column(type: "string", length: 64)]
    #[Groups(['department:read', 'department:list'])]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    #[ORM\Column(type: "string", length: 256)]
    #[Groups(['department:read', 'department:list'])]
    private string $description;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    #[Groups(['country:read'])]
    private \DateTimeInterface $createdAt;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    #[Groups(['country:read'])]
    private \DateTimeInterface $updatedAt;

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * --- HOOKS --- *
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void {
        $this->createdAt = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updatedAt = new \DateTime();
    }
}
