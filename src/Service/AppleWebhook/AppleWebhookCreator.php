<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

use App\Entity\AppleWebhook;
use Doctrine\ORM\EntityManagerInterface;

class AppleWebhookCreator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(AppleEvent $event)
    {
        $appleWebhook = new AppleWebhook($event->getArrayCopy());
        $this->em->persist($appleWebhook);
        $this->em->flush();
    }
}
