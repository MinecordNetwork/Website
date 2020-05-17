<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\Product\Exception\ProductNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class ProductRepository
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityRepository|ObjectRepository
	 */
	private function getRepository()
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
	 * @return Product[]
	 */
	public function getAll(): array
	{
		return $this->getQueryBuilderForAll()->getQuery()->execute();
	}

	private function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e');
	}

	public function getQueryBuilderForDataGrid(): QueryBuilder
	{
		return $this->getQueryBuilderForAll();
	}
}
