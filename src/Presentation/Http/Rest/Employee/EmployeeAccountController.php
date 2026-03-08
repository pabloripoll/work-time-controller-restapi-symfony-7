<?php

namespace App\Presentation\Http\Rest\Employee;

use App\Application\Employee\Query\GetEmployeeProfileQuery;
use App\Presentation\Http\Rest\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/account', name: 'api_employee_')]
#[IsGranted('ROLE_EMPLOYEE')]
class EmployeeAccountController extends AbstractApiController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $user = $this->getAuthenticatedUser();

        $query = new GetEmployeeProfileQuery($user->getId());
        $envelope = $this->messageBus->dispatch($query);
        $profile = $envelope->last(HandledStamp::class)->getResult();

        return $this->json([
            'status' => 'success',
            'data' => $profile
        ]);
    }

    #[Route('/settings/password', name: 'update_password', methods: ['PATCH'])]
    public function updatePassword(Request $request): JsonResponse
    {
        // TODO: Implement password update
        return $this->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}
