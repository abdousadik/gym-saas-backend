<?php

namespace App\Module\Subscription\Entity;

use App\Module\Gym\Entity\Gym;
use App\Module\Member\Entity\Member;
use App\Module\Plan\Entity\MembershipPlan;
use App\Module\Subscription\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ORM\Table(name: 'subscriptions')]
#[ORM\HasLifecycleCallbacks]
class Subscription
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';

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

    #[ORM\ManyToOne(targetEntity: MembershipPlan::class)]
    #[ORM\JoinColumn(name: 'membership_plan_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private MembershipPlan $membershipPlan;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $endDate;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $amountPaid = 0;

    #[ORM\Column(length: 20)]
    private string $paymentStatus = self::PAYMENT_STATUS_UNPAID;

    #[ORM\Column(length: 20)]
    private string $status = self::STATUS_PENDING;

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

    public function getMembershipPlan(): MembershipPlan
    {
        return $this->membershipPlan;
    }

    public function setMembershipPlan(MembershipPlan $membershipPlan): self
    {
        $this->membershipPlan = $membershipPlan;

        return $this;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAmountPaid(): int
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(int $amountPaid): self
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    public function getAmountPaidFormatted(): float
    {
        return $this->amountPaid / 100;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $allowed = [
            self::PAYMENT_STATUS_PAID,
            self::PAYMENT_STATUS_UNPAID,
            self::PAYMENT_STATUS_PARTIAL,
        ];

        if (!in_array($paymentStatus, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid payment status.');
        }

        $this->paymentStatus = $paymentStatus;

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
            self::STATUS_EXPIRED,
            self::STATUS_CANCELLED,
            self::STATUS_PENDING,
        ];

        if (!in_array($status, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid subscription status.');
        }

        $this->status = $status;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->paymentStatus === self::PAYMENT_STATUS_PAID;
    }

    public function isUnpaid(): bool
    {
        return $this->paymentStatus === self::PAYMENT_STATUS_UNPAID;
    }

    public function isPartial(): bool
    {
        return $this->paymentStatus === self::PAYMENT_STATUS_PARTIAL;
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