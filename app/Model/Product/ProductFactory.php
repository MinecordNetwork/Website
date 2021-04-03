<?php

declare(strict_types=1);

namespace App\Model\Product;

use Ramsey\Uuid\Uuid;

final class ProductFactory
{
    public function create(ProductData $data): Product
    {
        return new Product(Uuid::uuid4(), $data);
    }
}
