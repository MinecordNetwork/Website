<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

use Thedudeguy\Rcon;

class RconFacade
{
	private RconCredentials $rconCredentials;

	public function __construct(RconCredentials $rconCredentials)
	{
		$this->rconCredentials = $rconCredentials;
	}

	public function sendCommands(array $commands, string $host, int $port): void
	{
		$rcon = new Rcon($host, $port, $this->rconCredentials->getPassword(), 3);

		bdump($host);
		bdump($port);
		bdump($this->rconCredentials->getPassword());
		
		if ($rcon->connect()) {
			foreach ($commands as $command) {
				$rcon->sendCommand($command);
			}
		}
	}
}
