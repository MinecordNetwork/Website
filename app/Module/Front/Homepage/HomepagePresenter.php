<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Homepage;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Module\Front\BaseFrontPresenter;

/** 
 * @property-read HomepageTemplate $template
 */
class HomepagePresenter extends BaseFrontPresenter
{
	private ArticleFacade $articleFacade;

	public function __construct(
		ArticleFacade $articleFacade
	) {
		parent::__construct();
		$this->articleFacade = $articleFacade;
	}

	public function actionDefault(): void
	{
		$this->template->articles = $this->articleFacade->getAllOrderedByCreatedAt(20, 0);
	}
}
