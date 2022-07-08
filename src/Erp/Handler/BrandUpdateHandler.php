<?php

declare(strict_types=1);

namespace App\Erp\Handler;

use App\Entity\Brand;
use App\Erp\Message\BrandUpdateMessage;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;

// TODO для активации добавить #[AsMessageHandler]
class BrandUpdateHandler
{
    private BrandRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(BrandRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function __invoke(BrandUpdateMessage $message)
    {
        $this->em->clear();

        $brand = $this->repository->findOneBy(['erpId' => $message->getId()]);

        if (!$brand) {
            $brand = new Brand();
        }

        $brand->setErpId($message->getId())
            ->setName($message->getName())
            ->setDescription($message->getDescription())
            ->setSelfBrand($message->getSelfBrand());

        $this->em->persist($brand);
        $this->em->flush();
    }
}