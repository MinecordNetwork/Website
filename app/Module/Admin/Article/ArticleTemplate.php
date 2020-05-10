<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Article;

use Minecord\Model\Article\Article;
use Nette\Bridges\ApplicationLatte\Template;

class ArticleTemplate extends Template
{
	public ?Article $article;
}
