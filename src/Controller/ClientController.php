<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/clients', name: 'api_client_')]
final class ClientController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(ClientRepository $repository): JsonResponse
    {
        $clients = $repository->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC']);

        return $this->json(array_map($this->toArray(...), $clients));
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Client $client): JsonResponse
    {
        return $this->json($this->toArray($client));
    }

    #[Route('/{id<\d+>}/orders', name: 'orders', methods: ['GET'])]
    public function orders(Client $client, OrderRepository $repository): JsonResponse
    {
        $orders = $repository->findBy(['client' => $client], ['orderDate' => 'DESC', 'id' => 'DESC']);

        return $this->json(array_map(fn ($order) => [
            'id' => $order->getId(),
            'date' => $order->getOrderDate()->format('Y-m-d'),
            'status' => $order->getStatus(),
        ], $orders));
    }

    private function toArray(Client $client): array
    {
        return [
            'id' => $client->getId(),
            'name' => $client->getFirstName().' '.$client->getLastName(),
            'city' => $client->getCity(),
            'orders' => $this->generateUrl('api_client_orders', ['id' => $client->getId()]),
        ];
    }
}
