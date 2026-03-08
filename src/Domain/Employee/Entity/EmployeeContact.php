<?php

declare(strict_types=1);

namespace App\Domain\Employee\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employee_contacts')]
class EmployeeContact
{
    private function __construct(
        Employee $employee,
        ?string $postal = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $mobile = null
    ) {
        $this->employee = $employee;
        $this->postal = $postal;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobile = $mobile;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
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

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $postal = null;

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $phone = null;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $mobile = null;

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * BUSINESS METHODS
     */

    public static function create(
        Employee $employee,
        ?string $postal = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $mobile = null
    ): self {
        return new self($employee, $postal, $email, $phone, $mobile);
    }

    public function updateContacts(
        ?string $postal = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $mobile = null
    ): void {
        $this->postal = $postal;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobile = $mobile;
    }
}
