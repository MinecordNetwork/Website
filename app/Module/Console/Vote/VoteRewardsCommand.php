<?php

declare(strict_types=1);

namespace App\Module\Console\Vote;

use App\Model\Vote\VoteFacade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:vote:rewards')]
final class VoteRewardsCommand extends Command
{
    public function __construct(
        private VoteFacade $voteFacade,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->voteFacade->rewardPlayers();

        return Command::SUCCESS;
    }
}