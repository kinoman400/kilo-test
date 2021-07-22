<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

use App\Service\PaymentEventProcessor\PaymentEventType;

class ApplePaymentEventTypeMapper
{
    public function resolve(string $notificationType): ?PaymentEventType
    {
        return $this->getMap()[$notificationType] ?? null;
    }

    private function getMap(): array
    {
        return [
            'INITIAL_BUY' => PaymentEventType::INITIAL_SUBSCRIPTION(),
            'DID_RENEW' => PaymentEventType::RENEWED_SUBSCRIPTION(),
            'DID_FAIL_TO_RENEW' => PaymentEventType::UNSUCCESSFUL_RENEWAL(),
            'CANCEL' => PaymentEventType::CANCEL_SUBSCRIPTION(),
        ];
    }
}
