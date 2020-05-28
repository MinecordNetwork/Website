<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Article;

use Minecord\Model\Article\Article;
use Minecord\Module\Front\BaseFrontTemplate;

class ArticleTemplate extends BaseFrontTemplate
{
	public ?Article $article;
}
