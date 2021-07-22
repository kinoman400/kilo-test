<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;
use App\Service\PaymentEventProcessor\PaymentEventType;
use App\Traits\LoggerRequiredTrait;

class InitialSubscriptionEventProcessor implements EventProcessorInterface
{
    use LoggerRequiredTrait;

    public function support(PaymentEventInterface $event): bool
    {
        return $event->getPaymentEventType()->equals(PaymentEventType::INITIAL_SUBSCRIPTION());
    }

    public function process(PaymentEventInterface $event): void
    {
        $this->logger->info(
            sprintf('Event "%s" received', $event->getPaymentEventType()->getValue()),
            ['subscriptionId' => $event->getSubscriptionId()]
        );
    }
}
