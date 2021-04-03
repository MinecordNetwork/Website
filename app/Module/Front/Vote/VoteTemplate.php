<?php

declare(strict_types=1);

namespace App\Module\Front\Vote;

use App\Model\Vote\Site\VoteSite;
use App\Model\Vote\VoteStats;
use App\Module\Front\BaseFrontTemplate;

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
