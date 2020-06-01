<?php

declare(strict_types=1);

namespace Minecord\Model\Server;

use Nette\Database\Connection;

class ServerFacade
{
	private Connection $database;

	public function __construct(
		Connection $database
	) {
		$this->database = $database;
	}

	public function getCount(): int
	{
		return (int) $this->database->query('SELECT id FROM minecraft_ban WHERE is_active = 1')->getRowCount();
	}

	/** 
	 * @return Server[] 
	 */
	public function getAll(): array
	{
		$servers = [];
		
		$result = $this->database->query('SELECT * FROM minecraft_server');

		foreach ($result as $row) {
			$server = new Server();
			$server->id = $row->id;
			$server->name = $row->name;
			$server->displayName = $row->display_name;
			$server->gameType = $row->game_type;
			$server->address = $row->address;
			$server->host = explode(':', $row->address)[0];
			$server->port = (int) explode(':', $row->address)[1];
			if (!in_array($server->name, ['build', 'test', 'event'])) {
				$server->rconPort = $server->port - 1;
			}
			$servers[] = $server;
		}

		return $servers;
	}
}
