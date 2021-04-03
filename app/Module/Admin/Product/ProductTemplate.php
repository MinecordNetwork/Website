<?php

declare(strict_types=1);

namespace App\Module\Admin\Product;

use App\Model\Product\Product;
use App\Module\Admin\BaseAdminTemplate;

class ProductTemplate extends BaseAdminTemplate
{
    public ?Product $product;
}
