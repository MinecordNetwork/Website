<?php

declare(strict_types=1);

namespace Minecord\Model\Vote;

use DateTime;

class Vote
{
	public int $id;
	public string $nickname;
	public DateTime $createdAt;
}
