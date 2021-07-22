<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;

use App\Entity\Subscription;

class PaymentEvent implements PaymentEventInterface
{
    private PaymentEventType $type;
    private Subscription $subscription;

    public function __construct(PaymentEventType $type, Subscription $subscription)
    {
        $this->type = $type;
        $this->subscription = $subscription;
    }

    public function getPaymentEventType(): PaymentEventType
    {
        return $this->type;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
