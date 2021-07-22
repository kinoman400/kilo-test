<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;

class PaymentEvent implements PaymentEventInterface
{
    private PaymentEventType $type;
    private string $subscriptionId;

    public function __construct(PaymentEventType $type, string $subscriptionId)
    {
        $this->type = $type;
        $this->subscriptionId = $subscriptionId;
    }

    public function getPaymentEventType(): PaymentEventType
    {
        return $this->type;
    }

    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }
}
