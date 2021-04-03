<?php

declare(strict_types=1);

namespace App\Module\Front\Page;

use App\Model\Page\Page;
use App\Module\Front\BaseFrontTemplate;

class PageTemplate extends BaseFrontTemplate
{
    public Page $page;
}
