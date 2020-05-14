<?php

declare(strict_types=1);

namespace Minecord\Model\Banlist;

use DateTime;

class Ban
{
	public int $id;
	public string $targetName;
	public string $adminName;
	public string $reason;
	public DateTime $createdAt;
	public DateTime $expireAt;
}
