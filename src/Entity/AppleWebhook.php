<?php

namespace App\Entity;

use App\Repository\AppleEventRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppleEventRepository::class)
 */
class AppleWebhook
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $data;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}
