<?php

declare(strict_types=1);

namespace App\Service\PaymentEventProcessor;


use MyCLabs\Enum\Enum;

/**
 * @method static CANCEL_SUBSCRIPTION()
 * @method static INITIAL_SUBSCRIPTION()
 * @method static RENEWED_SUBSCRIPTION()
 * @method static UNSUCCESSFUL_RENEWAL()
 */
class PaymentEventType extends Enum
{
    private const CANCEL_SUBSCRIPTION = 'cancel_subscription';
    private const INITIAL_SUBSCRIPTION = 'initial_subscription';
    private const RENEWED_SUBSCRIPTION = 'renewed_subscription';
    private const UNSUCCESSFUL_RENEWAL = 'unsuccessful_renewal';
}
