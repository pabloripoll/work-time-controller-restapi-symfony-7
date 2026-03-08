<?php

namespace App\Application\Office\Query;

use App\Application\Office\DTO\JobDTO;
use App\Domain\Office\Repository\JobRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetJobsByDepartmentHandler
{
    public function __construct(
        private readonly JobRepositoryInterface $regionRepository
    ) {
    }

    public function __invoke(GetJobsByDepartmentQuery $query): array
    {
        $regions = $this->regionRepository->findByDepartmentId($query->departmentId);

        return array_map(
            fn($region) => new JobDTO(
                id: $region->getId(),
                departmentId: $region->getDepartment()->getId(),
                departmentName: $region->getDepartment()->getName(),
                title: $region->getTitle(),
                description: $region->getDescription(),
                createdAt: $region->getCreatedAt(),
                updatedAt: $region->getUpdatedAt()
            ),
            $regions
        );
    }
}
