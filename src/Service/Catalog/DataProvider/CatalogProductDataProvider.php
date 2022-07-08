<?php

declare(strict_types=1);

namespace App\Service\Catalog\DataProvider;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class CatalogProductDataProvider
{
    private ProductRepository $repository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Product::class);
    }

    /**
     * @return array<Product>
     */
    public function getFavoriteProductsByUserId(int $userId, ?int $limit = 100, ?int $offset = null): array
    {
        $qr = $this->repository->createQueryBuilder('p')
            ->join('p.favorites', 'f')
            ->where('f.user = :userId')
            ->setParameter('userId', $userId);

        $qr = $offset ? $qr->setFirstResult($offset) : $qr;
        $qr = $qr->setMaxResults($limit);
        $qr = $qr->getQuery();

        return $qr->getResult();
    }
}
