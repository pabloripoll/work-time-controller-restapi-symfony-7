<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentContractDTO;
use App\Domain\Employment\Repository\EmploymentContractRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetContractsByUserHandler
{
    public function __construct(
        private readonly EmploymentContractRepositoryInterface $contractRepository
    ) {
    }

    public function __invoke(GetContractsByUserQuery $query): array
    {
        $contracts = $this->contractRepository->findActiveByUserId($query->userId);

        return array_map(
            fn($contract) => EmploymentContractDTO::fromEntity($contract),
            $contracts
        );
    }
}
