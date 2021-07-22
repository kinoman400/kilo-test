<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Entity\Subscription;
use App\Event\Subscription\SubscriptionCancelled;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class SubscriptionRenewer
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;
    private SubscriptionStatusPolicy $statusPolicy;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        SubscriptionStatusPolicy $statusPolicy
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->statusPolicy = $statusPolicy;
    }

    public function renew(Subscription $subscription): void
    {
        $periodEndDate = (new DateTimeImmutable())->modify('+1 year');

        $subscription->renew($this->statusPolicy, $periodEndDate);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new SubscriptionCancelled($subscription));
    }
}
