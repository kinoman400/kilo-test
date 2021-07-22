<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

use App\Repository\SubscriptionRepository;
use App\Service\PaymentEventProcessor\PaymentEvent;
use App\Traits\LoggerRequiredTrait;

class ApplePaymentEventBuilder
{
    use LoggerRequiredTrait;

    private ApplePaymentEventTypeMapper $paymentEventTypeMapper;
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(
        ApplePaymentEventTypeMapper $paymentEventTypeMapper,
        SubscriptionRepository $subscriptionRepository
    ) {
        $this->paymentEventTypeMapper = $paymentEventTypeMapper;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function transform(AppleEvent $event): ?PaymentEvent
    {
        $paymentType = $this->paymentEventTypeMapper->resolve($event->notification_type);

        if (!isset($paymentType)) {
            $this->logger->warning('Cannot transform apple event to payment event', $event->getArrayCopy());
            return null;
        }

        $subscription = $this->subscriptionRepository->findByExternalId($event->auto_renew_product_id);

        if (!isset($subscription)) {
            $this->logger->alert('Unknown subscription', ['subscriptionId' => $event->auto_renew_product_id]);
            return null;
        }

        return new PaymentEvent($paymentType, $subscription);
    }
}
