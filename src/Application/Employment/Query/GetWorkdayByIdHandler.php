<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentWorkdayDTO;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use App\Domain\Shared\Exception\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetWorkdayByIdHandler
{
    public function __construct(
        private readonly EmploymentWorkdayRepositoryInterface $workdayRepository
    ) {
    }

    public function __invoke(GetWorkdayByIdQuery $query): EmploymentWorkdayDTO
    {
        $workday = $this->workdayRepository->findById($query->workdayId);

        if (! $workday) {
            throw new EntityNotFoundException("Workday with ID {$query->workdayId} not found");
        }

        return EmploymentWorkdayDTO::fromEntity($workday);
    }
}
