<?php

namespace App\Module\Plan\Entity;

use App\Module\Gym\Entity\Gym;
use App\Module\Plan\Repository\MembershipPlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MembershipPlanRepository::class)]
#[ORM\Table(name: 'membership_plans')]
#[ORM\HasLifecycleCallbacks]
class MembershipPlan
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Gym::class)]
    #[ORM\JoinColumn(name: 'gym_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Gym $gym;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive]
    private int $durationDays;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $price; // in cents

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

    // ======================
    // Getters & Setters
    // ======================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGym(): Gym
    {
        return $this->gym;
    }

    public function setGym(Gym $gym): self
    {
        $this->gym = $gym;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = trim($name);
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDurationDays(): int
    {
        return $this->durationDays;
    }

    public function setDurationDays(int $durationDays): self
    {
        $this->durationDays = $durationDays;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getPriceFormatted(): float
    {
        return $this->price / 100;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}