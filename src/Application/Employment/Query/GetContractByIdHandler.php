<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentContractDTO;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetContractByIdHandler
{
    public function __construct(
        private readonly EmploymentContractRepositoryInterface $contractRepository
    ) {
    }

    public function __invoke(GetContractByIdQuery $query): EmploymentContractDTO
    {
        $contract = $this->contractRepository->findById($query->contractId);

        if (! $contract) {
            throw new EntityNotFoundException("Contract with ID {$query->contractId} not found");
        }

        return EmploymentContractDTO::fromEntity($contract);
    }
}
