<?php

declare(strict_types=1);

namespace Minecord\Module\Console\Shop;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Minecord\Model\Shop\ShopFacade;

final class ShopStatsResetWeeklyCommand extends Command
{
	/** @var string */
	public static $defaultName = 'shop-reset:weekly';

	private ShopFacade $shopFacade;

	public function __construct(
		ShopFacade $shopFacade
	) {
		parent::__construct();
		$this->shopFacade = $shopFacade;
	}

	public function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->shopFacade->resetWeeklyStats();

		return 1;
	}
}
