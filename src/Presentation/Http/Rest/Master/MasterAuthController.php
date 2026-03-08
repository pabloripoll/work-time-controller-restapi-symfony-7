<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Master;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/v1/master/auth')]
class MasterAuthController extends AbstractController
{
    /**
     * Login handled by security.yaml firewall + CustomAuthenticationSuccessHandler
     */
    #[Route('/login', name: 'master_auth_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // This method is never called - security.yaml handles login
        return $this->json([]);
    }

}
