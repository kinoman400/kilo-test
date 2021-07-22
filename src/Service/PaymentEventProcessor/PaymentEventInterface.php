<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;

interface PaymentEventInterface
{
    public function getPaymentEventType(): PaymentEventType;
    public function getSubscriptionId(): string;
}
