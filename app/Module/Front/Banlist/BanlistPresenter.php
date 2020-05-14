<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Banlist;

use Minecord\Model\Banlist\BanlistFacade;
use Minecord\Module\Front\BaseFrontPresenter;

/**
 * @property-read BanlistTemplate $template
 */
class BanlistPresenter extends BaseFrontPresenter
{
	private BanlistFacade $banlistFacade;

	public function __construct(
		BanlistFacade $banlistFacade
	) {
		parent::__construct();
		$this->banlistFacade = $banlistFacade;
	}
	
	public function actionDefault(int $page = 1): void
	{
		$itemsPerPage = 15;
		$count = $this->banlistFacade->getCount();
		
		$pageCount = (int) (($count / $itemsPerPage) + ($count % $itemsPerPage === 0 ? 0 : 1));

		if ($page > $pageCount) {
			$this->redirect('default');
		}

		$this->template->page = $page;
		$this->template->pageCount = $pageCount;
		$this->template->bans = $this->banlistFacade->getAll($itemsPerPage, ($page - 1) * $itemsPerPage);
	}
}
