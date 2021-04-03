<?php

declare(strict_types=1);

namespace App\Module\Front\Vote;

use App\Model\Player\Exception\PlayerNotFoundException;
use App\Model\Player\PlayerFacade;
use App\Model\Vote\Site\VoteSite;
use App\Model\Vote\VoteFacade;
use App\Module\Front\BaseFrontPresenter;

/**
 * @property VoteTemplate $template
 */
class VotePresenter extends BaseFrontPresenter
{
    public function __construct(
        private VoteFacade $voteFacade,
        private PlayerFacade $playerFacade
    ) {
        parent::__construct();
    }

    public function actionDefault(int $pid = null): void
    {
        $this->template->voteStats = $this->voteFacade->getVoteStats((int) date('Y'), (int) date('m'), 100);

        $lastMonth = date_create(date('Y-m-d') . 'first day of last month');
        $this->template->voteStatsLastMonth = $this->voteFacade->getVoteStats((int) $lastMonth->format('Y'), (int) $lastMonth->format('m'), 10);
        
        $this->template->voteSites = VoteSite::getAll();
        
        if ($pid !== null) {
            try {
                $this->template->nickname = $this->playerFacade->get($pid)->nickname;
            } catch (PlayerNotFoundException) {}
        }
    }
}
