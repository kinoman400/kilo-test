<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Entity\Subscription;

class SubscriptionStatusPolicy
{
    public function canBeChangedTo(Subscription $subscription, string $status): bool
    {
        if ($subscription->getStatus() === $status) {
            return true;
        }

        if ($status === Subscription::STATUS_INITIAL) {
            return false;
        }

        if ($status === Subscription::STATUS_ACTIVE) {
            return true;
        }

        if ($status === Subscription::STATUS_ACTIVE && in_array($status, [Subscription::STATUS_STOPPED])) {
            return true;
        }

        return false;
    }
}
