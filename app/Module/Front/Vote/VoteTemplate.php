<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vote;

use Minecord\Model\Vote\Site\VoteSite;
use Minecord\Model\Vote\VoteStats;
use Minecord\Module\Front\BaseFrontTemplate;

class VoteTemplate extends BaseFrontTemplate
{
	/** @var VoteSite[] */
	public array $voteSites;
	
	/** @var VoteStats[] */
	public array $voteStats;
	
	/** @var VoteStats[] */
	public array $voteStatsLastMonth;
	
	public ?string $nickname = null;
}
