<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Loader\Configurator\Routes;

#[Route('/trap', name: 'trap_')]
final class TrapController extends AbstractController
{
    #[Route('/{slug}', name: 'sale')]
    public function article(string $slug): JsonResponse
    {
        return $this->json(['action' => 'article', 'slug' => $slug]);
    }
    #[Route('/sale', name: 'sale')]
    public function sale(): JsonResponse
    {
        return $this->json(['action' => 'sale']);
    }
}
