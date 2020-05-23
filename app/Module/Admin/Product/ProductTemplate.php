<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product;

use Minecord\Model\Product\Product;
use Nette\Bridges\ApplicationLatte\Template;

class ProductTemplate extends Template
{
	public ?Product $product;
}
