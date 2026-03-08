<?php

declare(strict_types=1);

namespace App\Domain\Employee\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'employee_profile')]
#[ORM\UniqueConstraint(name: 'uniq_employee_profile_name_surname', columns: ['name', 'surname'])]
class EmployeeProfile
{
    private function __construct(
        Employee $employee,
        string $name,
        string $surname,
        ?\DateTimeImmutable $birthdate = null
    ) {
        $this->employee = $employee;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    #[Groups(['profile:read', 'profile:list'])]
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

    #[ORM\Column(type: 'string', length: 64)]
    #[Groups(['profile:read', 'profile:list'])]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    #[ORM\Column(type: 'string', length: 64)]
    #[Groups(['profile:read', 'profile:list'])]
    private string $surname;

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getFullName(): string
    {
        return trim("{$this->name} {$this->surname}");
    }

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    #[Groups(['profile:read'])]
    private ?\DateTimeImmutable $birthdate = null;

    public function getBirthdate(): ?\DateTimeImmutable
    {
        return $this->birthdate;
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Employee $employee,
        string $name,
        string $surname,
        ?\DateTimeImmutable $birthdate = null
    ): self {
        return new self($employee, $name, $surname, $birthdate);
    }

    public function updateProfile(
        string $name,
        string $surname,
        ?\DateTimeImmutable $birthdate = null
    ): void {
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
    }
}
