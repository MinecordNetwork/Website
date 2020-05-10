<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Page;

use Minecord\Model\Page\Page;
use Nette\Bridges\ApplicationLatte\Template;

class PageTemplate extends Template
{
	public Page $page;
}
