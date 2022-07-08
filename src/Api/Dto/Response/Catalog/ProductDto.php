<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */
declare(strict_types=1);

namespace App\Api\Dto\Response\Catalog;

use JMS\Serializer\Annotation as Serializer;

class ProductDto
{
    #[Serializer\SerializedName("id")]
    #[Serializer\Type("int")]
    private int $id;

    #[Serializer\SerializedName("articul")]
    #[Serializer\Type("string")]
    private string $articul;

    #[Serializer\SerializedName("title")]
    #[Serializer\Type("string")]
    private string $title;

    #[Serializer\SerializedName("images")]
    #[Serializer\Type("array")]
    private array $images = [];

    #[Serializer\SerializedName("quantity")]
    #[Serializer\Type("float")]
    private float $quantity = 0.0;

    #[Serializer\SerializedName("navActivity")]
    #[Serializer\Type("bool")]
    private bool $navActivity;

    #[Serializer\SerializedName("productType")]
    #[Serializer\Type("string")]
    private string $productType;

    #[Serializer\SerializedName("baseUnit")]
    #[Serializer\Type("string")]
    private string $baseUnit;

    #[Serializer\SerializedName("showUnit")]
    #[Serializer\Type("string")]
    private string $showUnit;

    #[Serializer\SerializedName("favorite")]
    #[Serializer\Type("bool")]
    private bool $favorite = false;

    #[Serializer\SerializedName("featureNonStandard")]
    #[Serializer\Type("string")]
    private ?string $featureNonStandard = null;

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function setArticul(string $articul): static
    {
        $this->articul = $articul;

        return $this;
    }

    public function setNavActivity(bool $navActivity): static
    {
        $this->navActivity = $navActivity;

        return $this;
    }

    public function setProductType(string $productType): static
    {
        $this->productType = $productType;

        return $this;
    }

    public function setBaseUnit(string $baseUnit): static
    {
        $this->baseUnit = $baseUnit;

        return $this;
    }

    public function setShowUnit(string $showUnit): static
    {
        $this->showUnit = $showUnit;

        return $this;
    }

    public function setFeatureNonStandard(?string $featureNonStandard): static
    {
        $this->featureNonStandard = $featureNonStandard;

        return $this;
    }
}
