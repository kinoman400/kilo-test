<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

use App\Service\PaymentEventProcessor\PaymentEvent;
use App\Traits\LoggerRequiredTrait;

class ApplePaymentEventBuilder
{
    use LoggerRequiredTrait;

    private ApplePaymentEventTypeMapper $paymentEventTypeMapper;

    public function __construct(ApplePaymentEventTypeMapper $paymentEventTypeMapper)
    {
        $this->paymentEventTypeMapper = $paymentEventTypeMapper;
    }

    public function transform(AppleEvent $event): ?PaymentEvent
    {
        $paymentType = $this->paymentEventTypeMapper->resolve($event->notification_type);

        if (isset($paymentType)) {
            return new PaymentEvent($paymentType, $event->auto_renew_product_id);
        }

        $this->logger->warning('Cannot transform apple event to payment event', $event->getArrayCopy());

        return null;
    }
}
