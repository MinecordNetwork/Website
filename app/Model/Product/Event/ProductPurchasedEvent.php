<?php

declare(strict_types=1);

namespace App\Model\Product\Event;

use App\Model\Product\Product;

class ProductPurchasedEvent
{
    public function __construct(
        private Product $product,
        private string $nickname,
        private string $method
    ) {}

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
