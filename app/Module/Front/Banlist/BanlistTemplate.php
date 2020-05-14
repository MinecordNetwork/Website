<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Banlist;

use Minecord\Model\Banlist\Ban;
use Minecord\Module\Front\BaseFrontTemplate;

class BanlistTemplate extends BaseFrontTemplate
{
	/** @var Ban[] */
	public array $bans;
	public int $page;
	public int $pageCount;
}
