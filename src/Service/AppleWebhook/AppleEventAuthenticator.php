<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

class AppleEventAuthenticator
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function isValid(AppleEvent $event): bool
    {
        return $event->password === $this->password;
    }
}
