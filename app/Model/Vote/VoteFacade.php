<?php

declare(strict_types=1);

namespace App\Model\Vote;

use App\Model\Discord\DiscordMessenger;
use App\Model\Item\ItemFacade;
use Nette\Database\Connection;
use Nette\Utils\DateTime;

class VoteFacade
{
    public function __construct(
        private Connection $database,
        private ItemFacade $itemFacade,
        private DiscordMessenger $discordMessenger,
    ) {}

    public function getCount(): int
    {
        return (int) $this->database->query('SELECT id FROM minecraft_vote')->getRowCount();
    }

    /** 
     * @return VoteStats[] 
     */
    public function getVoteStats(int $year, int $month, int $limit = 100): array
    {
        $voteStats = [];

        $result = $this->database->query('SELECT COUNT(minecraft_vote.id) as vote_count, minecraft_player.name as player_name, minecraft_player.id as player_id, MAX(minecraft_vote.created_at) as last_vote FROM minecraft_vote JOIN minecraft_player ON minecraft_vote.player_id = minecraft_player.id WHERE YEAR(minecraft_vote.created_at) = ? AND MONTH(minecraft_vote.created_at) = ? AND minecraft_vote.serverlist_id != ? GROUP BY minecraft_vote.player_id ORDER BY vote_count DESC LIMIT ?', $year, $month, 0, $limit);

        $rank = 1;
        foreach ($result as $row) {
            $vote = new VoteStats();
            $vote->rank = $rank;
            $vote->playerId = $row['player_id'];
            $vote->nickname = $row['player_name'];
            $vote->lastVote = DateTime::from($row['last_vote']);
            $vote->count = (int) $row['vote_count'];
            $voteStats[] = $vote;
            $rank++;
        }

        return $voteStats;
    }

    public function rewardPlayers(): void
    {
        $rewardTexts = [];
        
        $rewardItems = [
            1 => [403, 414, 400, 21, 20, 6, 163],
            2 => [402, 413, 400, 21, 20, 5, 163],
            3 => [402, 412, 400, 21, 20, 262, 163],
            4 => [402, 401, 21, 20, 262, 163],
            5 => [402, 401, 21, 20, 262, 163],
        ];
        
        $i = 1;
        $tenDaysAgo = new DateTime('-10 days');
        $topVoters = $this->getVoteStats((int) $tenDaysAgo->format('Y'), (int) $tenDaysAgo->format('m'), 5);
        foreach ($topVoters as $topVoter) {
            $itemTexts = [];
            
            foreach ($rewardItems[$i] as $rewardItem) {
                $item = $this->itemFacade->getItem($rewardItem);
                $itemTexts[] = $item->name;
                $this->database->query('INSERT INTO minecraft_player_delivery (player_id, item_id, amount, server_type, created_at) VALUES (?, ?, ?, ?, ?)', $topVoter->playerId, $item->id, 1, 'survival', gmdate("Y-m-d H:i:s"));
            }

            $rewardTexts[$topVoter->playerId] = implode(', ', $itemTexts);
            
            $i++;
        }
        
        $this->discordMessenger->notifyTopVoters($topVoters, $rewardTexts);
    }
}
