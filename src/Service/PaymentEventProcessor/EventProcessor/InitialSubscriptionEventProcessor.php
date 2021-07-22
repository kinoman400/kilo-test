<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;
use App\Service\PaymentEventProcessor\PaymentEventType;
use App\Service\Subscription\SubscriptionStarter;
use App\Traits\LoggerRequiredTrait;

class InitialSubscriptionEventProcessor implements EventProcessorInterface
{
    use LoggerRequiredTrait;

    private SubscriptionStarter $subscriptionStarter;

    public function __construct(SubscriptionStarter $subscriptionStarter)
    {
        $this->subscriptionStarter = $subscriptionStarter;
    }

    public function support(PaymentEventInterface $event): bool
    {
        return $event->getPaymentEventType()->equals(PaymentEventType::INITIAL_SUBSCRIPTION());
    }

    public function process(PaymentEventInterface $event): void
    {
        $this->subscriptionStarter->start($event->getSubscription());
    }
}
