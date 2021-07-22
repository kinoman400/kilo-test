<?php

namespace App\Tests\Action;

use App\Controller\Webhook\Action\AppleWebhookAction;
use App\Service\AppleWebhook\AppleEvent;
use App\Service\AppleWebhook\AppleEventAuthenticator;
use App\Service\AppleWebhook\ApplePaymentEventBuilder;
use App\Service\AppleWebhook\AppleWebhookCreator;
use App\Service\PaymentEventProcessor\PaymentEvent;
use App\Service\PaymentEventProcessor\PaymentEventProcessor;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AppleWebhookActionTest extends TestCase
{
    private const PASSWORD = 'password';

    protected AppleEventAuthenticator $authenticator;
    protected PaymentEventProcessor $paymentEventProcessor;
    protected ApplePaymentEventBuilder $paymentEventBuilder;
    protected AppleWebhookCreator $webhookCreator;
    protected AppleWebhookAction $action;

    protected function setUp(): void
    {
        $this->authenticator = new AppleEventAuthenticator(self::PASSWORD);
        $this->paymentEventProcessor = $this->createMock(PaymentEventProcessor::class);
        $this->paymentEventBuilder = $this->createMock(ApplePaymentEventBuilder::class);
        $this->webhookCreator = $this->createMock(AppleWebhookCreator::class);
        $this->action = new AppleWebhookAction(
            $this->authenticator,
            $this->paymentEventProcessor,
            $this->paymentEventBuilder,
            $this->webhookCreator
        );
        $this->action->setLogger($this->createMock(LoggerInterface::class));
    }

    /**
     * @dataProvider invalidPasswordProvider
     */
    public function test_invalid_password_is_not_allowed(AppleEvent $event): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->action->process($event);
    }

    public function invalidPasswordProvider(): array
    {
        return [
            [new AppleEvent(['password' => 1])],
            [new AppleEvent(['password' => 'asasd'])],
            [new AppleEvent(['password' => 'gg%df3'])],
            [new AppleEvent(['password' => '8%%`7'])],
        ];
    }

    /**
     * @dataProvider invalidPasswordProvider
     */
    public function test_empty_password_is_not_allowed(AppleEvent $event): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->action->process($event);
    }

    public function emptyPasswordProvider(): array
    {
        return [
            [new AppleEvent(['password' => null])],
            [new AppleEvent(['password' => ''])],
            [new AppleEvent([])],
        ];
    }

    public function test_webhook_data_should_be_stored()
    {
        $event = new AppleEvent(['password' => self::PASSWORD]);

        $this->webhookCreator->expects($this->once())
            ->method('create')
            ->with($this->equalTo($event));

        $this->action->process($event);
    }

    public function test_not_supported_event_should_be_skipped()
    {
        $event = new AppleEvent(['password' => self::PASSWORD]);

        $this->paymentEventBuilder->expects($this->once())
            ->method('transform')
            ->with($this->equalTo($event))
            ->willReturn(null);

        $this->paymentEventProcessor->expects($this->never())
            ->method('process');

        $this->action->process($event);
    }

    public function test_supported_event_should_be_processed()
    {
        $event = new AppleEvent(['password' => self::PASSWORD]);
        $paymentEvent = $this->createMock(PaymentEvent::class);
        $this->paymentEventBuilder->expects($this->once())
            ->method('transform')
            ->with($this->equalTo($event))
            ->willReturn($paymentEvent);

        $this->paymentEventProcessor->expects($this->once())
            ->method('process')
            ->with($this->equalTo($paymentEvent));

        $this->action->process($event);
    }
}
