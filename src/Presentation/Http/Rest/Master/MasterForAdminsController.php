<?php

namespace App\Presentation\Http\Rest\Master;

use App\Application\Admin\Query\GetAllAdminsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/master', name: 'api_master_')]
#[IsGranted('ROLE_MASTER')]
class MasterForAdminsController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route('/admins', name: 'list_admins', methods: ['GET'])]
    public function listAdmins(): JsonResponse
    {
        // TODO: Create GetAllAdminsQuery
        return $this->json([
            'status' => 'success',
            'data' => []
        ]);
    }
}
