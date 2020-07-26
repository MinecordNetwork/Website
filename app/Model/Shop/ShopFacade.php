<?php

declare(strict_types=1);

namespace Minecord\Model\Shop;

use Minecord\Model\Shop\Exception\ShopNotFoundException;
use Nette\Database\Connection;
use Nette\Utils\DateTime;

class ShopFacade
{
	private Connection $database;

	public function __construct(
		Connection $database
	) {
		$this->database = $database;
	}

	/**
	 * @throws ShopNotFoundException
	 */
	public function get(int $id): Shop
	{
		$data = $this->database->fetch('SELECT id, name FROM minecraft_shop WHERE id = ?', $id);
		if ($data !== null) {
			$player = new Shop();
			$player->id = $data['id'];
			return $player;
		}

		throw new ShopNotFoundException(sprintf('Shop with id "%s" not found', $id));
	}

	public function resetDailyStats(): void
	{
		$this->database->query('UPDATE minecraft_shop SET daily_score = 0');
		$this->database->query('UPDATE minecraft_shop SET daily_earnings = 0');

	}

	public function resetWeeklyStats(): void
	{
		$this->database->query('UPDATE minecraft_shop SET weekly_score = 0');
		$this->database->query('UPDATE minecraft_shop SET weekly_earnings = 0');

	}
}
