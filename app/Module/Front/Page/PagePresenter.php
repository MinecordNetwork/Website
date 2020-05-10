<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Page;

use Minecord\Model\Page\PageFacade;
use Minecord\Module\Front\BaseFrontPresenter;
use Ramsey\Uuid\Uuid;

/**
 * @property-read PageTemplate $template
 */
class PagePresenter extends BaseFrontPresenter
{
	private PageFacade $pageFacade;

	public function __construct(
		PageFacade $pageFacade
	) {
		parent::__construct();
		$this->pageFacade = $pageFacade;
	}

	public function actionDefault(string $id): void
	{
		$this->template->page = $this->pageFacade->get(Uuid::fromString($id));
	}
}
