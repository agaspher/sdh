<?php

declare(strict_types=1);

namespace App\Api\Controller\Catalog;

use App\Api\Controller\AbstractApiController;
use App\Api\DataTransformer\Catalog\ProductInfoDtoDataTransformer;
use App\Api\Exception\Catalog\ProductNotFoundException;
use App\Api\Service\Catalog\UpdateProductDtoForUserService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiCatalogProductController extends AbstractApiController
{
    private ProductDtoDataTransformer $productTransformer;

    public function __construct(
        ProductDtoDataTransformer $productTransformer
    ) {
        $this->productTransformer = $productTransformer;
    }

    /**
     * @throws ProductNotFoundException
     */
    #[Route('/catalog/product/{id}', name: 'catalog.product.info', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function infoAction(int $id, ProductRepository $productRepository): JsonResponse
    {
        if (!$product = $productRepository->find($id)) {
            throw new ProductNotFoundException();
        }

        $responseDto = $this->productTransformer->transform($product);

        return $this->success($responseDto);
    }
}
