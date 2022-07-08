<?php

/** @noinspection PhpPropertyOnlyWrittenInspection */
declare(strict_types=1);

namespace App\Erp\Message;

use App\Erp\Message\Base\BaseErpMessage;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class BrandUpdateMessage extends BaseErpMessage
{
    public const MESSAGE_TYPE = 'brand.update';

    /**
     * ID в системе ERP
     */
    #[Serializer\SerializedName('id')]
    #[Serializer\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $id;

    /**
     * CTM - торговая марка
     */
    #[Serializer\SerializedName('selfBrand')]
    #[Serializer\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $selfBrand;

    /**
     * Название бренда
     */
    #[Serializer\SerializedName('name')]
    #[Serializer\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $name;

    /**
     * Описание бренда
     */
    #[Serializer\SerializedName('description')]
    #[Serializer\Type('string')]
    #[Assert\Type('string')]
    private ?string $description = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getSelfBrand(): string
    {
        return $this->selfBrand;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}