<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Player\PlayerFacade;
use Minecord\Model\Product\Product;
use Minecord\Model\Product\ProductFacade;
use Minecord\Module\Front\BaseFrontPresenter;

/**
 * @property VipTemplate $template
 */
class VipPresenter extends BaseFrontPresenter
{
	/** @var Product[] */
	private array $ranks;
	
	private ProductFacade $productFacade;
	private PlayerFacade $playerFacade;

	public function __construct(
		ProductFacade $productFacade, 
		PlayerFacade $playerFacade
	) {
		parent::__construct();
		$this->productFacade = $productFacade;
		$this->playerFacade = $playerFacade;
	}

	public function actionDefault(): void
	{
		$this->ranks = $this->productFacade->getAllRanks();
	}
	
	public function renderDefault(): void
	{
		$this->template->ranks = $this->ranks;
		$this->template->latestVipActivations = $this->playerFacade->getLatestVipActivations(5);
	}
}
