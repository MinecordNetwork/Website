<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\Image\Image;
use App\Model\Product\Event\ProductPurchasedEvent;
use App\Model\Product\Exception\ProductNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\UuidInterface;

final class ProductFacade extends ProductRepository
{
    public function __construct(
        private ProductFactory $productFactory,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager);
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
    public function onPurchase(UuidInterface $id, string $nickname, string $method): Product
    {
        $product = $this->get($id);
        
        $this->eventDispatcher->dispatch(new ProductPurchasedEvent($product, $nickname, $method));

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
