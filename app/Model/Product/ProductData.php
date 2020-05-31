<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

use DateTime;

final class ProductData
{
	public string $nameEnglish;
	public string $nameCzech;
	public string $descriptionEnglish;
	public string $descriptionCzech;
	public string $smsCode;
	public float $price = 0;
	public float $priceCzechSms = 0;
	public float $priceSlovakSms = 0;
	public float $discountedPrice = 0;
	public float $discountedPriceCzechSms = 0;
	public float $discountedPriceSlovakSms = 0;
	public ?DateTime $discountActiveTo = null;
	public int $discountPercent = 0;
	public int $duration = 0;
	public bool $isRank = false;
}
