<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;
use App\Service\PaymentEventProcessor\PaymentEventType;
use App\Service\Subscription\SubscriptionCanceler;
use App\Traits\LoggerRequiredTrait;

class CancelSubscriptionEventProcessor implements EventProcessorInterface
{
    use LoggerRequiredTrait;

    private SubscriptionCanceler $subscriptionCanceler;

    public function __construct(SubscriptionCanceler $subscriptionCanceler)
    {
        $this->subscriptionCanceler = $subscriptionCanceler;
    }

    public function support(PaymentEventInterface $event): bool
    {
        return $event->getPaymentEventType()->equals(PaymentEventType::CANCEL_SUBSCRIPTION());
    }

    public function process(PaymentEventInterface $event): void
    {
        $this->subscriptionCanceler->cancel($event->getSubscription());
    }
}
