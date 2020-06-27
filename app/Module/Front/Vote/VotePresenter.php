<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vote;

use Minecord\Model\Player\Exception\PlayerNotFoundException;
use Minecord\Model\Player\PlayerFacade;
use Minecord\Model\Vote\Site\VoteSite;
use Minecord\Model\Vote\VoteFacade;
use Minecord\Module\Front\BaseFrontPresenter;

/**
 * @property-read VoteTemplate $template
 */
class VotePresenter extends BaseFrontPresenter
{
	private VoteFacade $voteFacade;
	private PlayerFacade $playerFacade;

	public function __construct(
		VoteFacade $voteFacade, 
		PlayerFacade $playerFacade
	) {
		parent::__construct();
		$this->voteFacade = $voteFacade;
		$this->playerFacade = $playerFacade;
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
			} catch (PlayerNotFoundException $e) {
			}
		}
	}
}
