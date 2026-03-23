<?php

namespace App\Module\Gym\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Module\Gym\Repository\GymRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GymRepository::class)]
#[ORM\Table(name: 'gyms')]
#[ORM\HasLifecycleCallbacks]
class Gym
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 150, unique: true)]
    #[Assert\NotBlank]
    private string $slug;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

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
    public function setUpdatedAt(): void
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = strtolower($slug);
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE])) {
            throw new \InvalidArgumentException('Invalid status');
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