<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentWorkdayClockingDTO;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetClockingsByWorkdayHandler
{
    public function __construct(
        private readonly EmploymentWorkdayClockingRepositoryInterface $clockingRepository
    ) {
    }

    public function __invoke(GetClockingsByWorkdayQuery $query): array
    {
        $clockings = $this->clockingRepository->findByWorkdayId($query->workdayId);

        return array_map(
            fn($clocking) => EmploymentWorkdayClockingDTO::fromEntity($clocking),
            $clockings
        );
    }
}
