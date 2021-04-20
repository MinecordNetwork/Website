<?php

declare(strict_types=1);

namespace App\Module\Console\PlayerData;

use App\Model\PlayerData\PlayerDataFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PlayerDataResetMonthlyCommand extends Command
{
    /** @var string */
    public static $defaultName = 'player-data-reset:monthly';
    
    public function __construct(
        private PlayerDataFacade $playerDataFacade
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->playerDataFacade->resetMonthlyStats();

        return 0;
    }
}
