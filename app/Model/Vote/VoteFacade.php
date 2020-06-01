<?php

declare(strict_types=1);

namespace Minecord\Model\Vote;

use Nette\Database\Connection;
use Nette\Utils\DateTime;

class VoteFacade
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
	 * @return VoteStats[] 
	 */
	public function getVoteStats(int $year, int $month, int $limit = 100): array
	{
		$voteStats = [];

		$result = $this->database->query('SELECT COUNT(minecraft_vote.id) as vote_count, minecraft_player.name as player_name, MAX(minecraft_vote.created_at) as last_vote FROM minecraft_vote JOIN minecraft_player ON minecraft_vote.player_id = minecraft_player.id WHERE YEAR(minecraft_vote.created_at) = ? AND MONTH(minecraft_vote.created_at) = ? GROUP BY minecraft_vote.player_id ORDER BY vote_count DESC LIMIT ?', $year, $month, $limit);

		$rank = 1;
		foreach ($result as $row) {
			$vote = new VoteStats();
			$vote->rank = $rank;
			$vote->nickname = $row['player_name'];
			$vote->lastVote = DateTime::from($row['last_vote']);
			$vote->count = (int) $row['vote_count'];
			$voteStats[] = $vote;
			$rank++;
		}

		return $voteStats;
	}
}
