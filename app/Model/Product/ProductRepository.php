<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Model\Product\Exception\ProductNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class ProductRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function get(UuidInterface $id): Product
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($product === null) {
            throw new ProductNotFoundException(sprintf('Product with id "%s" not found.', $id));
        }

        return $product;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getBySmsCodeAndPriceCzech(string $smsCode, float $price): Product
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBy([
            'smsCode' => $smsCode,
            'priceCzechSms' => $price,
        ]);

        if ($product === null) {
            throw new ProductNotFoundException(sprintf('Product with smsCode "%s" and priceSlovakSms "%s" not found.', $smsCode, $price));
        }

        return $product;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getBySmsCodeAndPriceSlovak(string $smsCode, float $price): Product
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBy([
            'smsCode' => $smsCode,
            'priceSlovakSms' => $price,
        ]);

        if ($product === null) {
            throw new ProductNotFoundException(sprintf('Product with smsCode "%s" and priceSlovakSms "%s" not found.', $smsCode, $price));
        }

        return $product;
    }

    /**
     * @return Product[]
     */
    public function getAll(): array
    {
        return $this->getQueryBuilderForAll()->getQuery()->execute();
    }

    /**
     * @return Product[]
     */
    public function getAllRanks(): array
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('e.isRank = true')
            ->getQuery()
            ->execute();
    }

    private function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()
            ->createQueryBuilder('e')
            ->orderBy('e.sortOrder', 'ASC');
    }

    public function getQueryBuilderForDataGrid(): QueryBuilder
    {
        return $this->getQueryBuilderForAll();
    }
}
