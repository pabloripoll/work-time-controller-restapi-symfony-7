<?php

namespace App\Presentation\Http\Rest\Master;

use App\Application\Master\Command\UpdateMasterProfileCommand;
use App\Application\Master\Command\UpdateMasterProfileHandler;
use App\Application\Master\Query\GetMasterProfileHandler;
use App\Application\Master\Query\GetMasterProfileQuery;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\User;
use App\Presentation\Request\Master\UpdateMasterProfileRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/master')]
#[IsGranted('ROLE_MASTER')]
class MasterAccountController extends AbstractController
{
    /**
     * Get authenticated master's profile
     * Flow: User -> Master (by user_id) -> MasterProfile (by master_id)
     */
    #[Route('/account/profile', name: 'master_account_profile', methods: ['GET'])]
    public function getProfile(
        #[CurrentUser] User $user,
        GetMasterProfileHandler $handler
    ): JsonResponse {
        try {
            $query = new GetMasterProfileQuery($user->getId());
            $profile = $handler($query);

            return $this->json([
                'status' => 'success',
                'data' => [
                    'id' => $profile->id,
                    'master_id' => $profile->masterId,
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
     * Update authenticated master's profile
     */
    #[Route('/account/settings/profile', name: 'master_update_profile', methods: ['PATCH'])]
    public function updateProfile(
        #[CurrentUser] User $user,
        UpdateMasterProfileRequest $request,
        UpdateMasterProfileHandler $handler
    ): JsonResponse {
        try {
            $data = $request->validated();

            $command = new UpdateMasterProfileCommand(
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

    #[Route('/account/settings/password', name: 'master_update_password', methods: ['PATCH'])]
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
