<?php

declare(strict_types=1);

namespace App\Controller\Webhook\Action;

use App\Service\AppleWebhook\AppleEvent;
use App\Service\AppleWebhook\AppleEventAuthenticator;
use App\Service\AppleWebhook\ApplePaymentEventBuilder;
use App\Service\AppleWebhook\AppleWebhookCreator;
use App\Service\PaymentEventProcessor\PaymentEventProcessor;
use App\Traits\LoggerRequiredTrait;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AppleWebhookAction
{
    use LoggerRequiredTrait;

    private AppleEventAuthenticator $authenticator;
    private PaymentEventProcessor $paymentEventProcessor;
    private ApplePaymentEventBuilder $applePaymentEventBuilder;
    private AppleWebhookCreator $appleWebhookCreator;

    public function __construct(
        AppleEventAuthenticator $authenticator,
        PaymentEventProcessor $paymentEventProcessor,
        ApplePaymentEventBuilder $applePaymentEventBuilder,
        AppleWebhookCreator $appleWebhookCreator
    ) {
        $this->authenticator = $authenticator;
        $this->paymentEventProcessor = $paymentEventProcessor;
        $this->applePaymentEventBuilder = $applePaymentEventBuilder;
        $this->appleWebhookCreator = $appleWebhookCreator;
    }

    public function process(AppleEvent $event): void
    {
        if (!$this->authenticator->isValid($event)) {
            $this->logger->warning('Unsuccessful authentication');
            throw new AccessDeniedHttpException('Invalid password');
        }

        $this->appleWebhookCreator->create($event);
        $paymentEvent = $this->applePaymentEventBuilder->transform($event);

        if (isset($paymentEvent)) {
            $this->paymentEventProcessor->process($paymentEvent);
        }
    }
}
