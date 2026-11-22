<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/** « order » est un mot réservé en SQL : la table s'appelle orders, comme dans la base Boutique. */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    public const STATUSES = ['en_cours', 'expediee', 'livree', 'annulee'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $orderDate;

    #[ORM\Column(length: 20)]
    private string $status = 'en_cours';

    public function __construct()
    {
        $this->orderDate = new \DateTimeImmutable('today');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getOrderDate(): \DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeImmutable $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
