<?php

namespace App\Module\Member\Entity;

use App\Module\Gym\Entity\Gym;
use App\Module\Member\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: 'members')]
#[ORM\HasLifecycleCallbacks]
class Member
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_BLOCKED = 'blocked';

    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Gym::class)]
    #[ORM\JoinColumn(name: 'gym_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Gym $gym;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $firstName;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $lastName;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    private string $phone;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Length(max: 150)]
    private ?string $emergencyContactName = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(max: 30)]
    private ?string $emergencyContactPhone = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $joinDate;

    #[ORM\Column(length: 20)]
    private string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $today = new \DateTimeImmutable('today');
        $this->joinDate = $today;
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

    public function getGym(): Gym
    {
        return $this->gym;
    }

    public function setGym(Gym $gym): self
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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = trim($phone);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email !== null ? mb_strtolower(trim($email)) : null;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $allowed = [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            null,
        ];

        if (!in_array($gender, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid gender.');
        }

        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getEmergencyContactName(): ?string
    {
        return $this->emergencyContactName;
    }

    public function setEmergencyContactName(?string $emergencyContactName): self
    {
        $this->emergencyContactName = $emergencyContactName !== null ? trim($emergencyContactName) : null;

        return $this;
    }

    public function getEmergencyContactPhone(): ?string
    {
        return $this->emergencyContactPhone;
    }

    public function setEmergencyContactPhone(?string $emergencyContactPhone): self
    {
        $this->emergencyContactPhone = $emergencyContactPhone !== null ? trim($emergencyContactPhone) : null;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getJoinDate(): \DateTimeImmutable
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeImmutable $joinDate): self
    {
        $this->joinDate = $joinDate;

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
            self::STATUS_BLOCKED,
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

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
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