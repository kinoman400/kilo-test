<?php

declare(strict_types=1);

namespace App\Traits;

use Psr\Log\LoggerInterface;

trait LoggerRequiredTrait
{
    protected LoggerInterface $logger;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
