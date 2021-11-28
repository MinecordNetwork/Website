<?php

declare(strict_types=1);

namespace App\Module\Front\Homepage;

use App\Model\Article\Article;
use App\Module\Front\BaseFrontTemplate;
use Nette\Utils\Paginator;

class HomepageTemplate extends BaseFrontTemplate
{
    /** @var Article[] */
    public array $articles;
    public Paginator $paginator;
}
