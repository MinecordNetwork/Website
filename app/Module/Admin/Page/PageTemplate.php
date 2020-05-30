<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Page;

use Minecord\Model\Page\Page;
use Minecord\Module\Admin\BaseAdminTemplate;

class PageTemplate extends BaseAdminTemplate
{
	public ?Page $page;
}
