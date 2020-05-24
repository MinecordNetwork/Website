<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Product\Product;
use Minecord\Model\Product\ProductRepository;
use Minecord\Module\Front\BaseFrontPresenter;

/**
 * @property VipTemplate $template
 */
class VipPresenter extends BaseFrontPresenter
{
	/** @var Product[] */
	private array $ranks;
	
	private ProductRepository $productRepository;

	public function __construct(ProductRepository $productRepository)
	{
		parent::__construct();
		$this->productRepository = $productRepository;
	}

	public function actionDefault(): void
	{
		$this->ranks = $this->productRepository->getAllRanks();
	}
	
	public function renderDefault(): void
	{
		$this->template->ranks = $this->ranks;
	}
}
