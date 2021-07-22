<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use App\Service\Subscription\SubscriptionStatusPolicy;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use LogicException;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    public const STATUS_INITIAL = 'initial';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_STOPPED = 'stopped';
    public const STATUS_CANCELED = 'canceled';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $externalId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $startedAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $canceledAt = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $currentPeriodStart = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $currentPeriodEnd = null;

    public function __construct(User $user, string $externalId)
    {
        $this->user = $user;
        $this->externalId = $externalId;
        $this->status = self::STATUS_INITIAL;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function start(SubscriptionStatusPolicy $policy): void
    {
        $this->changeStatus($policy, self::STATUS_ACTIVE);
        $this->startedAt = new DateTimeImmutable();
        $this->currentPeriodStart = new DateTimeImmutable();
    }

    public function renew(SubscriptionStatusPolicy $policy, DateTimeImmutable $periodEndDate): void
    {
        $now = new DateTimeImmutable();

        if ($now > $periodEndDate) {
            throw new InvalidArgumentException('Period end date should be in the future');
        }

        $this->changeStatus($policy, self::STATUS_ACTIVE);
        $this->currentPeriodStart = new DateTimeImmutable();
        $this->currentPeriodEnd = $periodEndDate;
    }

    public function cancel(SubscriptionStatusPolicy $policy): void
    {
        $this->changeStatus($policy, self::STATUS_CANCELED);
        $this->canceledAt = new DateTimeImmutable();
    }

    public function stop(SubscriptionStatusPolicy $policy): void
    {
        $this->changeStatus($policy, self::STATUS_STOPPED);
    }

    private function changeStatus(SubscriptionStatusPolicy $policy, string $status): void
    {
        if (!$policy->canBeChangedTo($this, $status)) {
            throw new LogicException('Subscription cannot be started');
        }

        $this->status = $status;
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCanceledAt(): ?DateTimeImmutable
    {
        return $this->canceledAt;
    }

    public function getCurrentPeriodStart(): ?DateTimeImmutable
    {
        return $this->currentPeriodStart;
    }

    public function getCurrentPeriodEnd(): ?DateTimeImmutable
    {
        return $this->currentPeriodEnd;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
