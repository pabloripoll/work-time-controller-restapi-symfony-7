<?php

namespace App\Presentation\Http\Rest\Employment;

use App\Application\Employment\Query\GetWorkdaysByUserQuery;
use App\Application\Employment\Query\GetWorkdayByIdQuery;
use App\Application\Employment\Query\GetClockingsByUserQuery;
use App\Application\Employment\Query\GetClockingsByWorkdayQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/employment')]
class EmploymentWorkdayController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route('/users/{userId}/workdays', name: 'employment_workdays_by_user', methods: ['GET'])]
    public function listWorkdaysByUser(int $userId): JsonResponse
    {
        $workdays = $this->handle(new GetWorkdaysByUserQuery($userId));

        return $this->json($workdays);
    }

    #[Route('/workdays/{workdayId}', name: 'employment_workday_show', methods: ['GET'])]
    public function showWorkday(int $workdayId): JsonResponse
    {
        $workday = $this->handle(new GetWorkdayByIdQuery($workdayId));

        return $this->json($workday);
    }

    #[Route('/users/{userId}/clockings', name: 'employment_clockings_by_user', methods: ['GET'])]
    public function listClockingsByUser(int $userId): JsonResponse
    {
        $clockings = $this->handle(new GetClockingsByUserQuery($userId));

        return $this->json($clockings);
    }

    #[Route('/workdays/{workdayId}/clockings', name: 'employment_clockings_by_workday', methods: ['GET'])]
    public function listClockingsByWorkday(int $workdayId): JsonResponse
    {
        $clockings = $this->handle(new GetClockingsByWorkdayQuery($workdayId));

        return $this->json($clockings);
    }
}
