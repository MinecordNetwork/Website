<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Homepage;

use Minecord\Model\Article\Article;
use Minecord\Module\Front\BaseFrontTemplate;

class HomepageTemplate extends BaseFrontTemplate
{
	/** @var Article[] */
	public array $articles;
}
