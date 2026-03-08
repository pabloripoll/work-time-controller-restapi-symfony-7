<?php

namespace App\Application\Office\Query;

use App\Application\Office\DTO\DepartmentDTO;
use App\Domain\Office\Repository\DepartmentRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAllDepartmentsHandler
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }

    public function __invoke(GetAllDepartmentsQuery $query): array
    {
        $departments = $this->departmentRepository->findAll();

        return array_map(
            fn($department) => new DepartmentDTO(
                id: $department->getId(),
                name: $department->getName(),
                description: $department->getDescription(),
                createdAt: $department->getCreatedAt(),
                updatedAt: $department->getUpdatedAt()
            ),
            $departments
        );
    }
}
