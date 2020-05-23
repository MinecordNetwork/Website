<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Store;

use Minecord\Model\Product\Product;
use Minecord\Module\Front\BaseFrontTemplate;

class StoreTemplate extends BaseFrontTemplate
{
	/** @var Product[] */
	public array $ranks;
}
