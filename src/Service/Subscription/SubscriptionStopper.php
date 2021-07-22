<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Entity\Subscription;
use App\Event\Subscription\SubscriptionStopped;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class SubscriptionStopper
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

    public function stop(Subscription $subscription): void
    {
        $subscription->stop($this->statusPolicy);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new SubscriptionStopped($subscription));
    }
}
