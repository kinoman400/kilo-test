<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Service\AppleWebhook\AppleEvent;
use App\Service\AppleWebhook\AppleEventAuthenticator;
use App\Service\AppleWebhook\ApplePaymentEventBuilder;
use App\Service\PaymentEventProcessor\PaymentEventProcessor;
use App\Traits\LoggerRequiredTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class AppleWebhookController extends AbstractController
{
    use LoggerRequiredTrait;

    /**
     * @Route("/webhook/apple", methods={"POST"})
     */
    public function webhook(
        Request $request,
        SerializerInterface $serializer,
        AppleEventAuthenticator $authenticator,
        PaymentEventProcessor $processor,
        ApplePaymentEventBuilder $eventBuilder
    ): Response {
        try {
            $data = $serializer->decode($request->getContent(), 'json');
        } catch (Throwable $e) {
            $this->logger->info('Invalid event data received', ['request' => $request]);
            throw new BadRequestHttpException('Invalid request');
        }

        $this->logger->info('Apple event data received', $data);

        $event = new AppleEvent($data);

        if (!$authenticator->isValid($event)) {
            $this->logger->warning('Unsuccessful authentication');
            throw new AccessDeniedHttpException('Invalid password');
        }

        $paymentEvent = $eventBuilder->transform($event);

        if (isset($paymentEvent)) {
            $processor->process($paymentEvent);
        }

        return new Response();
    }
}
