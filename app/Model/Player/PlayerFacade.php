<?php

declare(strict_types=1);

namespace Minecord\Model\Player;

use Minecord\Model\Player\Exception\PlayerNotFoundException;
use Nette\Database\Connection;

class PlayerFacade
{
	private Connection $database;

	public function __construct(
		Connection $database
	) {
		$this->database = $database;
	}

	/**
	 * @throws PlayerNotFoundException
	 */
	public function get(int $id): Player
	{
		$data = $this->database->fetch('SELECT id, name FROM minecraft_player WHERE id = ?', $id);
		if ($data !== null) {
			$player = new Player();
			$player->id = $data['id'];
			$player->nickname = $data['name'];
			return $player;
		}
		
		throw new PlayerNotFoundException(sprintf('Player with id "%s" not found', $id));
	}
}
