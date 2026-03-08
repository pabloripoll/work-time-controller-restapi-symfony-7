<?php

namespace App\Application\Office\Query;

use App\Application\Office\DTO\DepartmentDTO;
use App\Domain\Office\Repository\DepartmentRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetDepartmentByIdHandler
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }

    public function __invoke(GetDepartmentByIdQuery $query): DepartmentDTO
    {
        $department = $this->departmentRepository->findById($query->departmentId);

        if (! $department) {
            throw new EntityNotFoundException("Country with ID {$query->departmentId} not found");
        }

        return new DepartmentDTO(
            id: $department->getId(),
            name: $department->getName(),
            description: $department->getDescription(),
            createdAt: $department->getCreatedAt(),
            updatedAt: $department->getUpdatedAt()
        );
    }
}
