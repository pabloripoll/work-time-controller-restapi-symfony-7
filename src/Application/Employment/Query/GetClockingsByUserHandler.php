<?php

namespace App\Application\Employment\Query;

use App\Application\Employment\DTO\EmploymentWorkdayClockingDTO;
use App\Domain\Employment\Repository\EmploymentWorkdayClockingRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetClockingsByUserHandler
{
    public function __construct(
        private readonly EmploymentWorkdayClockingRepositoryInterface $clockingRepository
    ) {
    }

    public function __invoke(GetClockingsByUserQuery $query): array
    {
        $clockings = $this->clockingRepository->findActiveByUserId($query->userId);

        return array_map(
            fn($clocking) => EmploymentWorkdayClockingDTO::fromEntity($clocking),
            $clockings
        );
    }
}
