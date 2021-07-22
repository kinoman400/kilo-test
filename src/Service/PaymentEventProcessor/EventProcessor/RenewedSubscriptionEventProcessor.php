<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;
use App\Service\PaymentEventProcessor\PaymentEventType;
use App\Service\Subscription\SubscriptionRenewer;
use App\Traits\LoggerRequiredTrait;

class RenewedSubscriptionEventProcessor implements EventProcessorInterface
{
    use LoggerRequiredTrait;

    private SubscriptionRenewer $subscriptionRenewer;

    public function __construct(SubscriptionRenewer $subscriptionRenewer)
    {
        $this->subscriptionRenewer = $subscriptionRenewer;
    }

    public function support(PaymentEventInterface $event): bool
    {
        return $event->getPaymentEventType()->equals(PaymentEventType::RENEWED_SUBSCRIPTION());
    }

    public function process(PaymentEventInterface $event): void
    {
        $this->subscriptionRenewer->renew($event->getSubscription());
    }
}
