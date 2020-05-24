<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Product\Product;
use Minecord\Module\Front\BaseFrontTemplate;

class VipTemplate extends BaseFrontTemplate
{
	/** @var Product[] */
	public array $ranks;
}
