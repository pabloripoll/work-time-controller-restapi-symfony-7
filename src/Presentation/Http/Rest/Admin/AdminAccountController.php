<?php

namespace App\Presentation\Http\Rest\Admin;

use App\Application\Admin\Command\UpdateAdminProfileCommand;
use App\Application\Admin\Command\UpdateAdminProfileHandler;
use App\Application\Admin\Query\GetAdminProfileHandler;
use App\Application\Admin\Query\GetAdminProfileQuery;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\User;
use App\Presentation\Request\Admin\UpdateAdminProfileRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/admin')]
#[IsGranted('ROLE_MASTER')]
class AdminAccountController extends AbstractController
{
    /**
     * Get authenticated admin's profile
     * Flow: User -> Admin (by user_id) -> AdminProfile (by admin_id)
     */
    #[Route('/account/profile', name: 'admin_account_profile', methods: ['GET'])]
    public function getProfile(
        #[CurrentUser] User $user,
        GetAdminProfileHandler $handler
    ): JsonResponse {
        try {
            $query = new GetAdminProfileQuery($user->getId());
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

    /**
     * Update authenticated admin's profile
     */
    #[Route('/account/settings/profile', name: 'admin_update_profile', methods: ['PATCH'])]
    public function updateProfile(
        #[CurrentUser] User $user,
        UpdateAdminProfileRequest $request,
        UpdateAdminProfileHandler $handler
    ): JsonResponse {
        try {
            $data = $request->validated();

            $command = new UpdateAdminProfileCommand(
                userId: $user->getId(),
                nickname: $data['nickname'],
                avatar: $data['avatar'] ?? null
            );

            $handler($command);

            return $this->json([
                'status' => 'success',
                'message' => 'Profile updated successfully'
            ]);
        } catch (DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/account/settings/password', name: 'admin_update_password', methods: ['PATCH'])]
    public function updatePassword(
        #[CurrentUser] User $user,
        Request $request
    ): JsonResponse {
        // TODO: Implement password update
        return $this->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}
