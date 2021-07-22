<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="owner")
     */
    private Collection $subscriptions;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }
}
