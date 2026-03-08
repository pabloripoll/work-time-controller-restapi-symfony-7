<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Admin\Entity\AdminAccessLog;
use App\Domain\Admin\Repository\AdminAccessLogRepositoryInterface;
use App\Domain\Employee\Entity\EmployeeAccessLog;
use App\Domain\Employee\Repository\EmployeeAccessLogRepositoryInterface;
use App\Domain\Master\Entity\MasterAccessLog;
use App\Domain\Master\Repository\MasterAccessLogRepositoryInterface;
use App\Domain\User\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly MasterAccessLogRepositoryInterface $masterAccessLogRepo,
        private readonly AdminAccessLogRepositoryInterface $adminAccessLogRepo,
        private readonly EmployeeAccessLogRepositoryInterface $employeeAccessLogRepo,
        private readonly int $jwtTtl = 3600 // 1 hour
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        /** @var User $user */
        $user = $token->getUser();

        if (! $user instanceof User) {
            return new JsonResponse([
                'error' => 'Invalid user type'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Generate JWT token
        $jwtToken = $this->jwtManager->create($user);
        $expiresAt = (new \DateTimeImmutable())->modify("+{$this->jwtTtl} seconds");

        // Get request metadata
        $ipAddress = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $payload = [
            'login_time' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'ip' => $ipAddress,
        ];

        // Create access log based on user role
        if ($user->isMaster()) {
            $accessLog = MasterAccessLog::create(
                user: $user,
                token: $jwtToken,
                expiresAt: $expiresAt,
                ipAddress: $ipAddress,
                userAgent: $userAgent,
                payload: $payload
            );
            $this->masterAccessLogRepo->save($accessLog);

        } elseif ($user->isAdmin()) {
            $accessLog = AdminAccessLog::create(
                user: $user,
                token: $jwtToken,
                expiresAt: $expiresAt,
                ipAddress: $ipAddress,
                userAgent: $userAgent,
                payload: $payload
            );
            $this->adminAccessLogRepo->save($accessLog);

        } elseif ($user->isEmployee()) {
            $accessLog = EmployeeAccessLog::create(
                user: $user,
                token: $jwtToken,
                expiresAt: $expiresAt,
                ipAddress: $ipAddress,
                userAgent: $userAgent,
                payload: $payload
            );
            $this->employeeAccessLogRepo->save($accessLog);
        }

        // USE WHOAMI DTOs

        return new JsonResponse([
            'token' => $jwtToken,
            'expires_in' => $this->jwtTtl,
            'token_type' => 'bearer',
            'user' => [
                'id' => $user->getId(),
                'email' => (string) $user->getEmail(),
            ]
        ]);
    }
}
