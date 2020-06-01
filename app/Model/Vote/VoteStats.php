<?php

declare(strict_types=1);

namespace Minecord\Model\Vote;

use Nette\Utils\DateTime;

class VoteStats
{
	public int $rank;
	public string $nickname;
	public int $count;
	public DateTime $lastVote;
}
