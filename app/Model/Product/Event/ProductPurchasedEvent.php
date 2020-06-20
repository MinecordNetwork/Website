<?php

declare(strict_types=1);

namespace Minecord\Model\Product\Event;

use Minecord\Model\Product\Product;

class ProductPurchasedEvent
{
	private Product $product;
	private string $nickname;
	private string $method;

	public function __construct(
		Product $product, 
		string $nickname,
		string $method
	) {
		$this->product = $product;
		$this->nickname = $nickname;
		$this->method = $method;
	}

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
