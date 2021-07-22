<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;
use App\Service\PaymentEventProcessor\PaymentEventType;
use App\Service\Subscription\SubscriptionStopper;
use App\Traits\LoggerRequiredTrait;

class UnsuccessfulRenewalEventProcessor implements EventProcessorInterface
{
    use LoggerRequiredTrait;

    private SubscriptionStopper $subscriptionStopper;

    public function __construct(SubscriptionStopper $subscriptionStopper)
    {
        $this->subscriptionStopper = $subscriptionStopper;
    }

    public function support(PaymentEventInterface $event): bool
    {
        return $event->getPaymentEventType()->equals(PaymentEventType::UNSUCCESSFUL_RENEWAL());
    }

    public function process(PaymentEventInterface $event): void
    {
        $this->subscriptionStopper->stop($event->getSubscription());
    }
}
