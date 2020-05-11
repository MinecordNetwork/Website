<?php

declare(strict_types=1);

namespace Minecord\Module\Front\OnlineMap;

use Minecord\Model\Page\PageFacade;
use Minecord\Module\Front\BaseFrontPresenter;
use Ramsey\Uuid\Uuid;

/**
 * @property-read OnlineMapTemplate $template
 */
class OnlineMapPresenter extends BaseFrontPresenter
{
	private PageFacade $pageFacade;

	public function __construct(
		PageFacade $pageFacade
	) {
		parent::__construct();
		$this->pageFacade = $pageFacade;
	}
}
