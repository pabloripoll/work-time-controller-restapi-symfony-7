<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'admin_profile')]
#[ORM\UniqueConstraint(name: 'uniq_admin_profile_nickname', columns: ['nickname'])]
#[ORM\UniqueConstraint(name: 'uniq_admin_profile_admin_id', columns: ['admin_id'])]
class AdminProfile
{
    private function __construct(
        Admin $admin,
        string $nickname,
        ?string $avatar = null
    ) {
        $this->admin = $admin;
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

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: "admin_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Admin $admin;

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    public function getAdminId(): int
    {
        return $this->admin->getId();
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
        Admin $admin,
        string $nickname,
        ?string $avatar = null
    ): self {
        return new self($admin, $nickname, $avatar);
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
