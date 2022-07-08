<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Catalog;

use App\Api\Dto\Response\Catalog\ProductDto;
use App\Entity\Product;

class ProductDtoDataTransformer
{
    /** @noinspection DuplicatedCode */
    public function transform(Product $product): ProductDto
    {
        $dto = new ProductDto();

        $dto
            ->setId($product->getId())
            ->setArticul($product->getErpId())
            ->setTitle($product->getTitle())
            ->setImages($product->getImages())
            ->setQuantity($product->getQuantity())
            ->setNavActivity($product->isNavActivity())
            ->setProductType($product->getProductType())
            ->setFeatureNonStandard($product->getFeatureNonStandard());

        $dto
            ->setBaseUnit($product->getBaseUnit())
            ->setShowUnit($product->getShowUnit());

        return $dto;
    }
}
