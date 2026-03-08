<?php

namespace App\Presentation\Http\Rest\Admin;

use App\Application\Employee\Query\GetAllEmployeesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/admin', name: 'api_admin_')]
#[IsGranted('ROLE_MASTER')]
class AdminForEmployeesController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route('/employees', name: 'list_employees', methods: ['GET'])]
    public function listEmployees(): JsonResponse
    {
        $query = new GetAllEmployeesQuery();
        $envelope = $this->messageBus->dispatch($query);
        $employees = $envelope->last(HandledStamp::class)->getResult();

        return $this->json([
            'status' => 'success',
            'data' => $employees
        ]);
    }

    #[Route('/employees/{id}/role/admin/apply', name: 'apply_admin_role', methods: ['PUT'])]
    public function applyAdminRole(int $id): JsonResponse
    {
        // TODO: Implement apply admin role logic
        return $this->json([
            'status' => 'success',
            'message' => 'Admin role applied successfully'
        ]);
    }

    #[Route('/employees/{id}/role/admin/revoke', name: 'revoke_admin_role', methods: ['PUT'])]
    public function revokeAdminRole(int $id): JsonResponse
    {
        // TODO: Implement revoke admin role logic
        return $this->json([
            'status' => 'success',
            'message' => 'Admin role revoked successfully'
        ]);
    }
}
