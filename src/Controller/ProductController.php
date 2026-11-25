<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class ProductController extends AbstractController
{
    // 1. Tous les produits
    #[Route('/products', name: 'product_list', methods: ['GET'])]
    public function list(ProductRepository $repository): JsonResponse
    {
        return $this->json(array_map($this->toArray(...), $repository->findAll()));
    }

    // 2. Un produit par son id : reçu directement comme entité
    #[Route('/products/{id<\d+>}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        return $this->json($this->toArray($product));
    }

    #[Route('/products/{reference<P-\d{3}>}', name: 'product_show_by_reference', methods: ['GET'])]
    public function showByReference(
        #[MapEntity(mapping: ['reference' => 'reference'])] Product $product,
    ): JsonResponse {
        return $this->json($this->toArray($product));
    }

    #[Route('/categories/{id<\d+>}/products', name: 'product_categories', methods: ['GET'])]
    public function categories(Category $category, ProductRepository $repository): JsonResponse
    {
        $products = $repository->findBy(['category' => $category], ['name' => 'ASC']);

        return $this->json(array_map($this->toArray(...), $products));
    }

    private function toArray(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'unitPrice' => $product->getUnitPrice(),
            'url' => $this->generateUrl('api_product_show', ['id' => $product->getId()]),
        ];
    }
}
