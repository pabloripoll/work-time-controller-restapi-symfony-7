<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentContractTypeDTO;
use App\Domain\Employment\Repository\EmploymentContractTypeRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetContractTypeByIdHandler
{
    public function __construct(
        private readonly EmploymentContractTypeRepositoryInterface $contractTypeRepository
    ) {
    }

    public function __invoke(GetContractTypeByIdQuery $query): EmploymentContractTypeDTO
    {
        $contractType = $this->contractTypeRepository->findById($query->contractTypeId);

        if (! $contractType) {
            throw new EntityNotFoundException("Contract type with ID {$query->contractTypeId} not found");
        }

        return EmploymentContractTypeDTO::fromEntity($contractType);
    }
}
