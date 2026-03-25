<?php

namespace App\Module\Attendance\Entity;

use App\Module\Attendance\Repository\AttendanceLogRepository;
use App\Module\Gym\Entity\Gym;
use App\Module\Member\Entity\Member;
use App\Module\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceLogRepository::class)]
#[ORM\Table(name: 'attendance_logs')]
#[ORM\HasLifecycleCallbacks]
class AttendanceLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Gym::class)]
    #[ORM\JoinColumn(name: 'gym_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Gym $gym;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Member $member;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $checkedInAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $checkedOutAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'created_by_user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $createdByUser = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $now = new \DateTimeImmutable();
        $this->checkedInAt = $now;
        $this->createdAt = $now;
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

    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getCheckedInAt(): \DateTimeImmutable
    {
        return $this->checkedInAt;
    }

    public function setCheckedInAt(\DateTimeImmutable $checkedInAt): self
    {
        $this->checkedInAt = $checkedInAt;

        return $this;
    }

    public function getCheckedOutAt(): ?\DateTimeImmutable
    {
        return $this->checkedOutAt;
    }

    public function setCheckedOutAt(?\DateTimeImmutable $checkedOutAt): self
    {
        $this->checkedOutAt = $checkedOutAt;

        return $this;
    }

    public function getCreatedByUser(): ?User
    {
        return $this->createdByUser;
    }

    public function setCreatedByUser(?User $createdByUser): self
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isCheckedOut(): bool
    {
        return $this->checkedOutAt !== null;
    }
}