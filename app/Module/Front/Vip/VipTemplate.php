<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Player\Vip\PlayerVipActivation;
use Minecord\Model\Product\Product;
use Minecord\Module\Front\BaseFrontTemplate;

class VipTemplate extends BaseFrontTemplate
{
	/** @var Product[] */
	public array $ranks;
	
	/** @var PlayerVipActivation[] */
	public array $latestVipActivations;
}
