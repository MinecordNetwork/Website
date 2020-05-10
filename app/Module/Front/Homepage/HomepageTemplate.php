<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Homepage;

use Minecord\Model\Article\Article;
use Nette\Bridges\ApplicationLatte\Template;

class HomepageTemplate extends Template
{
	/** @var Article[] */
	public array $articles;
}
