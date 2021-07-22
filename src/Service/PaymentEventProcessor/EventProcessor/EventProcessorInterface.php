<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor\EventProcessor;

use App\Service\PaymentEventProcessor\PaymentEventInterface;

interface EventProcessorInterface
{
    public function support(PaymentEventInterface $event): bool;
    public function process(PaymentEventInterface $event): void;
}
