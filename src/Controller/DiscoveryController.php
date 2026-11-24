<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/discovery', name: 'discovery_')]
final class DiscoveryController extends AbstractController
{
    #[Route('/ping', name: 'ping', methods: ['GET'])]
    public function ping(): JsonResponse
    {
        return $this->json(['status' => 'ok']);
    }

    #[Route('/hello/{name}', name: 'hello', methods: ['GET'])]
    public function hello(string $name = 'world'): JsonResponse
    {
        return $this->json(['message' => "Hello $name"]);
    }

    #[Route('/square/{n<\d+>}', name: 'square', methods: ['GET'])]
    public function square(int $n): JsonResponse
    {
        return $this->json(['n' => $n, 'square' => $n * $n]);
    }

    #[Route('/link/{n<\d+>}', name: 'link', methods: ['GET'])]
    public function link(int $n): JsonResponse
    {
        return $this->json(['url' => $this->generateUrl('discovery_square', ['n' => $n])]);
    }
}
