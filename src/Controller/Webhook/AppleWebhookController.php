<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Controller\Webhook\Action\AppleWebhookAction;
use App\Service\AppleWebhook\AppleEvent;
use App\Traits\LoggerRequiredTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        AppleWebhookAction $action
    ): Response {
        try {
            $data = $serializer->decode($request->getContent(), 'json');
        } catch (Throwable $e) {
            $this->logger->info('Invalid event data received', ['request' => $request]);
            throw new BadRequestHttpException('Invalid request');
        }

        $this->logger->info('Apple event data received', $data);
        $action->process(new AppleEvent($data));

        return new Response();
    }
}
