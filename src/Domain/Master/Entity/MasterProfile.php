<?php

declare(strict_types=1);

namespace App\Domain\Master\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'master_profile')]
#[ORM\UniqueConstraint(name: 'uniq_master_profile_nickname', columns: ['nickname'])]
#[ORM\UniqueConstraint(name: 'uniq_master_profile_master_id', columns: ['master_id'])]
class MasterProfile
{
    private function __construct(
        Master $master,
        string $nickname,
        ?string $avatar = null
    ) {
        $this->master = $master;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Master::class)]
    #[ORM\JoinColumn(name: "master_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Master $master;

    public function getMaster(): Master
    {
        return $this->master;
    }

    public function getMasterId(): int
    {
        return $this->master->getId();
    }

    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $nickname;

    public function getNickname(): string
    {
        return $this->nickname;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $avatar = null;

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Master $master,
        string $nickname,
        ?string $avatar = null
    ): self {
        return new self($master, $nickname, $avatar);
    }

    public function updateProfile(string $nickname, ?string $avatar = null): void
    {
        $this->nickname = $nickname;
        if ($avatar !== null) {
            $this->avatar = $avatar;
        }
        $this->markAsUpdated();
    }

    public function updateAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
        $this->markAsUpdated();
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
