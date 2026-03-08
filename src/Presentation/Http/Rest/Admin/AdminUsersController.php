<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Admin;

use App\Domain\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Domain\Shared\Exception\DomainException;
use App\Application\Admin\Query\GetAllAdminHandler;
use App\Application\Admin\Query\GetAllAdminQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\User\Command\RegisterAdminHandler;
use App\Application\User\Command\RegisterAdminCommand;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Presentation\Request\Admin\CreateAdminRequest;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Admin\Query\GetAdminProfileQuery;
use App\Application\Admin\Query\GetAdminProfileHandler;

#[Route('/api/v1/admin')]
#[IsGranted('ROLE_MASTER')]
class AdminUsersController extends AbstractController
{
    /**
     * List all admin users
     * Flow: admins -> admin_profiles (joined)
     */
    #[Route('/users', name: 'admin_list_admins', methods: ['GET'])]
    public function listAdmins(GetAllAdminHandler $handler): JsonResponse
    {
        try {
            $query = new GetAllAdminQuery();
            $admins = $handler($query);

            return $this->json([
                'status' => 'success',
                'data' => array_map(fn($admin) => [
                    'id' => $admin->id,
                    'user_id' => $admin->userId,
                    'email' => $admin->email,
                    'nickname' => $admin->nickname,
                    'avatar' => $admin->avatar,
                    'is_active' => $admin->isActive,
                    'is_banned' => $admin->isBanned,
                    'is_superadmin' => $admin->isSuperadmin,
                    'employee_id' => $admin->employeeId,
                    'created_at' => $admin->createdAt->format('Y-m-d H:i:s'),
                    'updated_at' => $admin->updatedAt->format('Y-m-d H:i:s'),
                ], $admins)
            ]);
        } catch (DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create new admin user
     */
    #[Route('/users', name: 'admin_create_admin', methods: ['POST'])]
    public function createAdmin(
        #[CurrentUser] User $currentUser,
        CreateAdminRequest $request,
        RegisterAdminHandler $handler
    ): JsonResponse {
        try {
            $data = $request->validated();

            $command = new RegisterAdminCommand(
                email: $data['email'],
                password: $data['password'],
                nickname: $data['nickname'],
                createdByUserId: $currentUser->getId()
            );

            $userId = $handler($command);

            return $this->json(
                [
                    'message' => 'Admin user created successfully',
                    'user' => [
                        'id' => $userId
                    ]
                ],
                Response::HTTP_CREATED
            );

        } catch (DomainException $e) {
            return $this->json(
                [
                    'message' => 'error',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Get profile of specific admin user by user_id
     * Flow: User ID -> Admin (by user_id) -> AdminProfile (by admin_id)
     */
    #[Route('/users/{userId}/profile', name: 'admin_get_user_profile', methods: ['GET'])]
    public function getUserProfile(
        int $userId,
        GetAdminProfileHandler $handler
    ): JsonResponse {
        try {
            $query = new GetAdminProfileQuery($userId);
            $profile = $handler($query);

            return $this->json([
                'status' => 'success',
                'data' => [
                    'id' => $profile->id,
                    'admin_id' => $profile->adminId,
                    'user_id' => $profile->userId,
                    'email' => $profile->email,
                    'nickname' => $profile->nickname,
                    'avatar' => $profile->avatar,
                    'created_at' => $profile->createdAt->format('Y-m-d H:i:s'),
                    'updated_at' => $profile->updatedAt->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
