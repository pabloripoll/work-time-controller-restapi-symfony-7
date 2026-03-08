<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentWorkdayDTO;
use App\Domain\Employment\Repository\EmploymentWorkdayRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetWorkdaysByUserHandler
{
    public function __construct(
        private readonly EmploymentWorkdayRepositoryInterface $workdayRepository
    ) {
    }

    public function __invoke(GetWorkdaysByUserQuery $query): array
    {
        $workdays = $this->workdayRepository->findActiveByUserId($query->userId);

        return array_map(
            fn($workday) => EmploymentWorkdayDTO::fromEntity($workday),
            $workdays
        );
    }
}
