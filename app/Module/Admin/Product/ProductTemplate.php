<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product;

use Minecord\Model\Product\Product;
use Minecord\Module\Admin\BaseAdminTemplate;

class ProductTemplate extends BaseAdminTemplate
{
	public ?Product $product;
}
