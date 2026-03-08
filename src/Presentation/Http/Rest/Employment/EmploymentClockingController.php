<?php

namespace App\Presentation\Http\Rest\Employment;

use App\Application\Employment\Command\ClockInCommand;
use App\Application\Employment\Command\ClockInHandler;
use App\Application\Employment\Command\ClockOutCommand;
use App\Application\Employment\Command\ClockOutHandler;
use App\Domain\Employee\Repository\EmployeeRepositoryInterface;
use App\Presentation\Http\Rest\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/employment/clocking')]
class EmploymentClockingController extends AbstractApiController
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepo
    ) {
    }

    #[Route('/clock-in', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function clockIn(ClockInHandler $handler): JsonResponse
    {
        try {
            // Fetch employee from repository using user ID
            //$user = $this->getAuthenticatedUser();
            //$employee = $this->employeeRepo->findByUserId($user->getId());
            // From AbstractApiController
            $employee = $this->getAuthenticatedEmployee($this->employeeRepo);

            if (! $employee) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Employee record not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $command = new ClockInCommand($employee->getId());
            $clocking = $handler($command);

            return $this->json([
                'status' => 'success',
                'message' => 'Clocked in successfully',
                'data' => [
                    'clocking_id' => $clocking->getId(),
                    'clock_time' => $clocking->getCreatedAt()->format('Y-m-d H:i:s'),
                ]
            ], Response::HTTP_CREATED);
        } catch (\DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/clock-out', methods: ['POST'])]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function clockOut(ClockOutHandler $handler): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser();

            // Fetch employee from repository
            $employee = $this->employeeRepo->findByUserId($user->getId());

            if (! $employee) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Employee record not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $command = new ClockOutCommand($employee->getId());
            $clocking = $handler($command);

            return $this->json([
                'status' => 'success',
                'message' => 'Clocked out successfully',
                'data' => [
                    'clocking_id' => $clocking->getId(),
                    'clock_time' => $clocking->getCreatedAt()->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
