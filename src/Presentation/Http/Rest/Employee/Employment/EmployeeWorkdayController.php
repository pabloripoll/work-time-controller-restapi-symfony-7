<?php

namespace App\Presentation\Http\Rest\Employee\Employment;

use App\Application\Employment\Query\GetWorkdaysByUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/account/contract/{uid}/workdays', name: 'api_employee_workdays_')]
#[IsGranted('ROLE_EMPLOYEE')]
class EmployeeWorkdayController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(string $uid): JsonResponse
    {
        $user = $this->getUser();
        
        // TODO: Verify uid belongs to authenticated user
        $query = new GetWorkdaysByUserQuery($user->getId());
        $envelope = $this->messageBus->dispatch($query);
        $workdays = $envelope->last(HandledStamp::class)->getResult();

        return $this->json([
            'status' => 'success',
            'data' => $workdays
        ]);
    }

    #[Route('/{date}', name: 'get', methods: ['GET'])]
    public function get(string $uid, string $date): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'status' => 'success',
            'data' => [
                'uid' => $uid,
                'date' => $date,
                'clockings' => []
            ]
        ]);
    }

    #[Route('/{date}/clock-in', name: 'clock_in', methods: ['POST'])]
    public function clockIn(string $uid, string $date): JsonResponse
    {
        $user = $this->getUser();

        // TODO: Create ClockInCommand
        return $this->json([
            'status' => 'success',
            'message' => 'Clocked in successfully'
        ], Response::HTTP_CREATED);
    }

    #[Route('/{date}/clock-out', name: 'clock_out', methods: ['POST'])]
    public function clockOut(string $uid, string $date): JsonResponse
    {
        $user = $this->getUser();

        // TODO: Create ClockOutCommand
        return $this->json([
            'status' => 'success',
            'message' => 'Clocked out successfully'
        ], Response::HTTP_CREATED);
    }
}
