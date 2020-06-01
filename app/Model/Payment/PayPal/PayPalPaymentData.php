<?php

declare(strict_types=1);

namespace Minecord\Model\Payment\PayPal;

use Minecord\Model\Product\Product;
use Rixafy\Currency\Currency;

class PayPalPaymentData
{
	public ?string $email;
	public string $nickname;
	public float $price;
	public Currency $currency;
	public Product $product;
}
