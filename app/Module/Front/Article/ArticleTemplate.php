<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Article;

use Minecord\Model\Article\Article;
use Minecord\Module\Front\BaseFrontTemplate;

class ArticleTemplate extends BaseFrontTemplate
{
	public Article $article;
}
