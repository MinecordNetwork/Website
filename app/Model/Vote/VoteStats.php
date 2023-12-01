<?php

declare(strict_types=1);

namespace App\Model\Vote;

use Nette\Utils\DateTime;

class VoteStats
{
    public int $rank;
    public int $playerId;
    public string $nickname;
    public int $count;
    public DateTime $lastVote;
}
