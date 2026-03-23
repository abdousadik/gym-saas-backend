<?php

namespace App\Module\User\Entity;

use App\Module\Gym\Entity\Gym;
use App\Module\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\UniqueConstraint(name: 'uniq_user_email', columns: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const PLATFORM_ROLE_SUPER_ADMIN = 'SUPER_ADMIN';
    public const PLATFORM_ROLE_GYM_USER = 'GYM_USER';

    public const GYM_ROLE_OWNER = 'OWNER';
    public const GYM_ROLE_ADMIN = 'ADMIN';
    public const GYM_ROLE_RECEPTIONIST = 'RECEPTIONIST';
    public const GYM_ROLE_COACH = 'COACH';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Gym::class)]
    #[ORM\JoinColumn(name: 'gym_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Gym $gym = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $firstName;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $lastName;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private string $email;

    #[ORM\Column]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(length: 30)]
    private string $platformRole = self::PLATFORM_ROLE_GYM_USER;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $gymRole = null;

    #[ORM\Column(length: 20)]
    private string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym(?Gym $gym): self
    {
        $this->gym = $gym;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = trim($lastName);

        return $this;
    }

    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = mb_strtolower(trim($email));

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlatformRole(): string
    {
        return $this->platformRole;
    }

    public function setPlatformRole(string $platformRole): self
    {
        $allowed = [
            self::PLATFORM_ROLE_SUPER_ADMIN,
            self::PLATFORM_ROLE_GYM_USER,
        ];

        if (!in_array($platformRole, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid platform role.');
        }

        $this->platformRole = $platformRole;

        return $this;
    }

    public function getGymRole(): ?string
    {
        return $this->gymRole;
    }

    public function setGymRole(?string $gymRole): self
    {
        $allowed = [
            self::GYM_ROLE_OWNER,
            self::GYM_ROLE_ADMIN,
            self::GYM_ROLE_RECEPTIONIST,
            self::GYM_ROLE_COACH,
            null,
        ];

        if (!in_array($gymRole, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid gym role.');
        }

        $this->gymRole = $gymRole;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $allowed = [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ];

        if (!in_array($status, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid status.');
        }

        $this->status = $status;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isSuperAdmin(): bool
    {
        return $this->platformRole === self::PLATFORM_ROLE_SUPER_ADMIN;
    }

    public function isGymUser(): bool
    {
        return $this->platformRole === self::PLATFORM_ROLE_GYM_USER;
    }

    public function isGymOwner(): bool
    {
        return $this->gymRole === self::GYM_ROLE_OWNER;
    }

    public function getRoles(): array
    {
        $roles = [];

        if ($this->platformRole === self::PLATFORM_ROLE_SUPER_ADMIN) {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        if ($this->platformRole === self::PLATFORM_ROLE_GYM_USER) {
            $roles[] = 'ROLE_GYM_USER';
        }

        if ($this->gymRole === self::GYM_ROLE_OWNER) {
            $roles[] = 'ROLE_GYM_OWNER';
        }

        if ($this->gymRole === self::GYM_ROLE_ADMIN) {
            $roles[] = 'ROLE_GYM_ADMIN';
        }

        if ($this->gymRole === self::GYM_ROLE_RECEPTIONIST) {
            $roles[] = 'ROLE_GYM_RECEPTIONIST';
        }

        if ($this->gymRole === self::GYM_ROLE_COACH) {
            $roles[] = 'ROLE_GYM_COACH';
        }

        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    public function eraseCredentials(): void
    {
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}