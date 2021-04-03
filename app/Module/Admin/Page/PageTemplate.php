<?php

declare(strict_types=1);

namespace App\Module\Admin\Page;

use App\Model\Page\Page;
use App\Module\Admin\BaseAdminTemplate;

class PageTemplate extends BaseAdminTemplate
{
    public ?Page $page;
}
