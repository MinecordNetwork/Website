<?php

declare(strict_types=1);

namespace App\Module\Console\Shop;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Shop\ShopFacade;

final class ShopStatsResetDailyCommand extends Command
{
    /** @var string */
    public static $defaultName = 'shop-reset:daily';
    
    public function __construct(
        private ShopFacade $shopFacade
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->shopFacade->resetDailyStats();

        return Command::SUCCESS;
    }
}
