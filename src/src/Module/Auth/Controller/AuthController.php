<?php

namespace App\Module\Auth\Controller;

use App\Module\Common\Controller\ApiController;
use App\Module\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends ApiController
{
    #[Route('/me', name: 'me', methods: ['GET'])]
    public function me(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user instanceof User) {
            return $this->error(
                'UNAUTHENTICATED',
                'Authentication is required.',
                401
            );
        }

        $gym = $user->getGym();

        return $this->success([
            'id' => $user->getId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'platformRole' => $user->getPlatformRole(),
            'gymRole' => $user->getGymRole(),
            'gym' => $gym ? [
                'id' => $gym->getId(),
                'name' => $gym->getName(),
                'slug' => $gym->getSlug(),
                'phone' => $gym->getPhone(),
                'email' => $gym->getEmail(),
                'address' => $gym->getAddress(),
                'city' => $gym->getCity(),
                'status' => $gym->getStatus(),
            ] : null,
        ]);
    }

    #[Route('/logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return $this->success(null, 'Logged out');
    }
}