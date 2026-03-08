<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Master;

use App\Domain\User\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Domain\Shared\Exception\DomainException;
use App\Application\Master\Query\GetAllMasterHandler;
use App\Application\Master\Query\GetAllMasterQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\User\Command\RegisterMasterHandler;
use App\Application\User\Command\RegisterMasterCommand;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Presentation\Request\Master\CreateMasterRequest;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Master\Query\GetMasterProfileQuery;
use App\Application\Master\Query\GetMasterProfileHandler;

#[Route('/api/v1/master')]
#[IsGranted('ROLE_MASTER')]
class MasterUsersController extends AbstractController
{
    /**
     * List all master users
     * Flow: masters -> master_profiles (joined)
     */
    #[Route('/users', name: 'master_list_masters', methods: ['GET'])]
    public function listMasters(GetAllMasterHandler $handler): JsonResponse
    {
        try {
            $query = new GetAllMasterQuery();
            $masters = $handler($query);

            return $this->json([
                'status' => 'success',
                'data' => array_map(fn($master) => [
                    'id' => $master->id,
                    'user_id' => $master->userId,
                    'email' => $master->email,
                    'nickname' => $master->nickname,
                    'avatar' => $master->avatar,
                    'is_active' => $master->isActive,
                    'is_banned' => $master->isBanned,
                    'created_at' => $master->createdAt->format('Y-m-d H:i:s'),
                    'updated_at' => $master->updatedAt->format('Y-m-d H:i:s'),
                ], $masters)
            ]);
        } catch (DomainException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create new master user
     */
    #[Route('/users', name: 'master_create_master', methods: ['POST'])]
    public function createMaster(
        #[CurrentUser] User $currentUser,
        CreateMasterRequest $request,
        RegisterMasterHandler $handler
    ): JsonResponse {
        try {
            $data = $request->validated();

            $command = new RegisterMasterCommand(
                email: $data['email'],
                password: $data['password'],
                nickname: $data['nickname'],
                createdByUserId: $currentUser->getId()
            );

            $userId = $handler($command);

            return $this->json(
                [
                    'message' => 'Master user created successfully',
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
     * Get profile of specific master user by user_id
     * Flow: User ID -> Master (by user_id) -> MasterProfile (by master_id)
     */
    #[Route('/users/{userId}/profile', name: 'master_get_user_profile', methods: ['GET'])]
    public function getUserProfile(
        int $userId,
        GetMasterProfileHandler $handler
    ): JsonResponse {
        try {
            $query = new GetMasterProfileQuery($userId);
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
}
