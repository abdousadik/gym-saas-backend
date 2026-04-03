<?php

declare(strict_types=1);

namespace App\Module\Common\Tenant;

use App\Module\Gym\Entity\Gym;
use App\Module\User\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class TenantContext
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function getUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('Authentication is required.');
        }

        return $user;
    }

    public function getGymUser(): User
    {
        $user = $this->getUser();

        if (!$user->isGymUser()) {
            throw new AccessDeniedException('A gym user is required.');
        }

        return $user;
    }

    public function getGym(): Gym
    {
        $user = $this->getGymUser();
        $gym = $user->getGym();

        if ($gym === null) {
            throw new AccessDeniedException('No gym is associated with this account.');
        }

        return $gym;
    }

    public function isSuperAdmin(): bool
    {
        return $this->getUser()->isSuperAdmin();
    }
}