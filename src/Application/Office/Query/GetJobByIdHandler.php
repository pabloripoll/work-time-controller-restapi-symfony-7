<?php

namespace App\Application\Office\Query;

use App\Application\Office\DTO\JobDTO;
use App\Domain\Office\Repository\JobRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetJobByIdHandler
{
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository
    ) {
    }

    public function __invoke(GetJobByIdQuery $query): JobDTO
    {
        $job = $this->jobRepository->findById($query->jobId);

        if (! $job || $job->getDepartment()->getId() !== $query->departmentId) {
            throw new EntityNotFoundException("Job with ID {$query->jobId} not found in department {$query->departmentId}");
        }

        return new JobDTO(
            id: $job->getId(),
            departmentId: $job->getDepartment()->getId(),
            departmentName: $job->getDepartment()->getName(),
            title: $job->getTitle(),
            description: $job->getDescription(),
            createdAt: $job->getCreatedAt(),
            updatedAt: $job->getUpdatedAt()
        );
    }
}
