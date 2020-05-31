<?php

declare(strict_types=1);

namespace Minecord\Model\Server;

use DateTime;

class Server
{
	public int $id;
	public string $name;
	public string $displayName;
	public string $gameType;
	public string $address;
	public string $host;
	public int $port;
	public ?int $rconPort = null;
}
