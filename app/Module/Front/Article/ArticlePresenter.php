<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Article;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Module\Front\BaseFrontPresenter;
use Ramsey\Uuid\Uuid;

/**
 * @property-read ArticleTemplate $template
 */
class ArticlePresenter extends BaseFrontPresenter
{
	private ArticleFacade $articleFacade;

	public function __construct(
		ArticleFacade $articleFacade
	) {
		parent::__construct();
		$this->articleFacade = $articleFacade;
	}

	public function actionDefault(string $id): void
	{
		$this->template->article = $this->articleFacade->get(Uuid::fromString($id));
	}
}
