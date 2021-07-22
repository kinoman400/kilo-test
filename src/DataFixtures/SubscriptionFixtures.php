<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public const EXTERNAL_SUBSCRIPTION_ID = 'external_id';

    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        $subscription = new Subscription($user, self::EXTERNAL_SUBSCRIPTION_ID);
        $manager->persist($subscription);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
