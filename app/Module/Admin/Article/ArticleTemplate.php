<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Article;

use Minecord\Model\Article\Article;
use Minecord\Module\Admin\BaseAdminTemplate;

class ArticleTemplate extends BaseAdminTemplate
{
	public ?Article $article;
}
