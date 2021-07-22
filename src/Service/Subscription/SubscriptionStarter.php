<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Entity\Subscription;
use App\Event\Subscription\SubscriptionStarted;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class SubscriptionStarter
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

    public function start(Subscription $subscription): void
    {
        $subscription->start($this->statusPolicy);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new SubscriptionStarted($subscription));
    }
}
