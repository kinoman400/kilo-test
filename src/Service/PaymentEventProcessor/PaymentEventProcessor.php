<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;

use App\Service\PaymentEventProcessor\EventProcessor\EventProcessorInterface;
use App\Traits\LoggerRequiredTrait;
use IteratorAggregate;

class PaymentEventProcessor
{
    use LoggerRequiredTrait;

    /**
     * @var IteratorAggregate|EventProcessorInterface[]
     */
    private IteratorAggregate $processors;

    public function __construct(IteratorAggregate $processors)
    {
        $this->processors = $processors;
    }

    public function process(PaymentEventInterface $event)
    {
        foreach ($this->processors as $processor) {
            if ($processor->support($event)) {
                $processor->process($event);
                return;
            }
        }

        $this->logger->warning(sprintf('Unknown event "%s"', $event->getPaymentEventType()->getValue()), [$event]);
    }
}
