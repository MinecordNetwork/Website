<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\Image\Image;
use Minecord\Model\Product\Exception\ProductNotFoundException;
use Ramsey\Uuid\UuidInterface;

final class ProductFacade extends ProductRepository
{
	private ProductFactory $productFactory;
	private EntityManagerInterface $entityManager;

	public function __construct(ProductFactory $productFactory, EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->productFactory = $productFactory;
		$this->entityManager = $entityManager;
	}

	public function create(ProductData $data): Product
	{
		$product = $this->productFactory->create($data);

		$this->entityManager->persist($product);
		$this->entityManager->flush();

		return $product;
	}

	/**
	 * @throws ProductNotFoundException
	 */
	public function edit(UuidInterface $id, ProductData $data): Product
	{
		$product = $this->get($id);

		$product->edit($data);
		$this->entityManager->flush();

		return $product;
	}

	/**
	 * @throws ProductNotFoundException
	 */
	public function give(UuidInterface $id, string $nickname): Product
	{
		$product = $this->get($id);
		
		//TODO: Give logic

		return $product;
	}

	/**
	 * @throws ProductNotFoundException
	 */
	public function delete(UuidInterface $id): void
	{
		$product = $this->get($id);

		$this->entityManager->remove($product);
		$this->entityManager->flush();
	}

	public function changeThumbnail(UuidInterface $id, Image $image): Product
	{
		$product = $this->get($id);
		
		$product->changeThumbnail($image);
		$this->entityManager->flush();
		
		return $product;
	}
}
