<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/orders', name: 'api_orders_')]
final class OrderController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        OrderRepository $orderRepository,
        #[MapQueryParameter] ?string $status = null,
    ): JsonResponse {
        if ($status !== null && !in_array($status, Order::STATUSES, true)) {
            throw new BadRequestHttpException(sprintf('Statut "%s" invalide.', $status));
        }

        $criteria = $status !== null ? ['status' => $status] : [];
        $orders = $orderRepository->findBy($criteria);

        return $this->json(array_map($this->toArray(...), $orders));
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        return $this->json($this->toArray($order));
    }

    private function toArray(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'status' => $order->getStatus(),
            'client' => $order->getClient()->getId(),
            'date' => $order->getOrderDate()->format('Y-m-d')
        ];
    }
}
