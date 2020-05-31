<?php

declare(strict_types=1);

namespace Minecord\Model\Product\Event;

use Minecord\Model\Product\Product;

class ProductPurchasedEvent
{
	private Product $product;
	private string $nickname;

	public function __construct(
		Product $product, 
		string $nickname
	) {
		$this->product = $product;
		$this->nickname = $nickname;
	}

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function getNickname(): string
	{
		return $this->nickname;
	}
}
