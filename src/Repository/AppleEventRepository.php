<?php

namespace App\Repository;

use App\Entity\AppleWebhook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppleWebhook|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppleWebhook|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppleWebhook[]    findAll()
 * @method AppleWebhook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppleEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppleWebhook::class);
    }
}
