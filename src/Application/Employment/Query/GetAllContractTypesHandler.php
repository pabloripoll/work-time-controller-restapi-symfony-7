<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentContractTypeDTO;
use App\Domain\Employment\Repository\EmploymentContractTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAllContractTypesHandler
{
    public function __construct(
        private readonly EmploymentContractTypeRepositoryInterface $contractTypeRepository
    ) {
    }

    public function __invoke(GetAllContractTypesQuery $query): array
    {
        $contractTypes = $this->contractTypeRepository->findAllActive();

        return array_map(
            fn($type) => EmploymentContractTypeDTO::fromEntity($type),
            $contractTypes
        );
    }
}
