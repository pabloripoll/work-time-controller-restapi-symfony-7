<?php

declare(strict_types=1);

namespace App\Domain\Master\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'master_access_logs')]
#[ORM\Index(columns: ['user_id'], name: 'idx_master_access_log_user')]
#[ORM\Index(columns: ['token'], name: 'idx_master_access_log_token')]
#[ORM\Index(columns: ['expires_at'], name: 'idx_master_access_log_expires')]
#[ORM\Index(columns: ['created_at'], name: 'idx_master_access_log_created')]
class MasterAccessLog
{
    private function __construct(
        User $user,
        string $token,
        \DateTimeImmutable $expiresAt,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $payload = null
    ) {
        $this->user = $user;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->payload = $payload;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\Column(type: 'bigint')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
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

    public function getUserId(): int
    {
        return $this->user->getId();
    }

    #[ORM\Column(type: 'boolean')]
    private bool $isTerminated = false;

    public function isTerminated(): bool
    {
        return $this->isTerminated;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $isExpired = false;

    public function isExpired(): bool
    {
        return $this->isExpired;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $expiresAt;

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    #[ORM\Column(type: 'integer')]
    private int $refreshCount = 0;

    public function getRefreshCount(): int
    {
        return $this->refreshCount;
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

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $ipAddress = null;

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $userAgent = null;

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    #[ORM\Column(type: 'integer')]
    private int $requestsCount = 0;

    public function getRequestsCount(): int
    {
        return $this->requestsCount;
    }

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $payload = null;

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    #[ORM\Column(type: 'text')]
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     *  --- BUSINESS METHODS --- * /
     */

    public static function create(
        User $user,
        string $token,
        \DateTimeImmutable $expiresAt,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $payload = null
    ): self {
        return new self($user, $token, $expiresAt, $ipAddress, $userAgent, $payload);
    }

    public function incrementRequestCount(): void
    {
        $this->requestsCount++;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function refresh(string $newToken, \DateTimeImmutable $newExpiresAt): void
    {
        $this->token = $newToken;
        $this->expiresAt = $newExpiresAt;
        $this->refreshCount++;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function terminate(): void
    {
        $this->isTerminated = true;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsExpired(): void
    {
        $this->isExpired = true;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function checkAndMarkExpired(): bool
    {
        $now = new \DateTimeImmutable();
        if ($now > $this->expiresAt && !$this->isExpired) {
            $this->markAsExpired();
            return true;
        }
        return $this->isExpired;
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
