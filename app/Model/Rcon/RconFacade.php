<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

use Minecord\Model\Rcon\WebSender\WebSender;
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
		
		if ($rcon->connect()) {
			foreach ($commands as $command) {
				$rcon->sendCommand($command);
			}
		}
		
		$rcon->disconnect();
	}
	
	public function sendCommandsToProxy(array $commands): void
	{
		$webSender = new WebSender($this->rconCredentials->getProxyHost(), $this->rconCredentials->getProxyRconPort(), $this->rconCredentials->getPassword());
		
		if ($webSender->connect()) {
			foreach ($commands as $command) {
				$webSender->sendCommand($command);
			}
		}
		
		$webSender->disconnect();
	}
}
