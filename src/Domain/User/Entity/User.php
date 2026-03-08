<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\ValueObject\UserRole;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private $nickname; // used by user domain profile

    private function __construct(
        int $id,
        Email $email,
        string $password,
        ?string $nickname = null,
        UserRole $role,
        int $createdByUserId
    ) {
        $this->id = $id;
        $this->email = (string)$email;
        $this->password = $password;
        $this->nickname = $nickname;
        $this->role = $role;
        $this->createdByUserId = $createdByUserId;
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['user:read', 'user:list'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    #[Groups(['user:read', 'user:list'])]
    private UserRole $role;

    public function getRole(): UserRole
    {
        return $this->role;
    }

    #[ORM\Column(type: 'string', unique: true)]
    #[Groups(['user:read', 'user:list', 'user:create'])]
    private string $email;

    public function getEmail(): Email
    {
        return Email::fromString($this->email);
    }

    #[ORM\Column(type: 'string')]
    #[Groups(['user:create', 'user:update'])]
    private string $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['user:read'])]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    #[ORM\Column(type: "bigint")]
    #[Groups(['user:read'])]
    private int $createdByUserId;

    public function getCreatedByUserId(): int
    {
        return $this->createdByUserId;
    }

    /**
     *  --- BUSINESS METHODS --- * /
     */

    public static function createMaster(
        int $id,
        Email $email,
        string $password,
        string $nickname,
        int $createdByUserId
    ): self {
        return new self($id, $email, $password, $nickname, UserRole::MASTER, $createdByUserId);
    }

    public static function createAdmin(
        int $id,
        Email $email,
        string $password,
        string $nickname,
        int $createdByUserId
    ): self {
        return new self($id, $email, $password, $nickname, UserRole::ADMIN, $createdByUserId);
    }

    public static function createEmployee(
        int $id,
        Email $email,
        string $password,
        ?string $nickname = null,
        int $createdByUserId
    ): self {
        return new self($id, $email, $password, $nickname, UserRole::EMPLOYEE, $createdByUserId);
    }

    public function updatePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
        $this->markAsUpdated();
    }

    public function updateEmail(Email $email): void
    {
        $this->email = (string)$email;
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

    private function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     *  --- CHECKS --- * /
     */

    public function isMaster(): bool
    {
        return $this->role === UserRole::MASTER;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::EMPLOYEE;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    // Symfony UserInterface implementation
    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Intentionally empty - no sensitive temporary credentials to erase
    }

    /**
     *  --- HOOKS --- * /
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void {
        $this->createdAt = new \DateTimeImmutable();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
