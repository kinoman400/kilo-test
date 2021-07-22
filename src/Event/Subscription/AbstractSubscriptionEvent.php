<?php

declare(strict_types=1);

namespace App\Event\Subscription;

use App\Entity\Subscription;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractSubscriptionEvent extends Event
{
    private Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
