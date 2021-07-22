<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;

use App\Entity\Subscription;

interface PaymentEventInterface
{
    public function getPaymentEventType(): PaymentEventType;
    public function getSubscription(): Subscription;
}
