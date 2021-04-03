<?php

declare(strict_types=1);

namespace App\Module\Front\Article;

use App\Model\Article\ArticleFacade;
use App\Model\Article\Exception\ArticleNotFoundException;
use App\Module\Front\BaseFrontPresenter;
use Ramsey\Uuid\Uuid;

/**
 * @property ArticleTemplate $template
 */
class ArticlePresenter extends BaseFrontPresenter
{
    public function __construct(
        private ArticleFacade $articleFacade
    ) {
        parent::__construct();
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function actionDefault(string $id): void
    {
        $this->template->article = $this->articleFacade->get(Uuid::fromString($id));
    }
}
