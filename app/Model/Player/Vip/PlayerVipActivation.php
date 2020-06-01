<?php

declare(strict_types=1);

namespace Minecord\Model\Player\Vip;

use Nette\Utils\DateTime;

class PlayerVipActivation
{
	public string $nickname;
	public DateTime $activatedAt;
}
