<?php

declare(strict_types=1);

namespace App\Model\Player;

use App\Model\Player\Exception\PlayerNotFoundException;
use App\Model\Player\Vip\PlayerVipActivation;
use Nette\Database\Connection;
use Nette\Utils\DateTime;

class PlayerFacade
{
    public function __construct(
        private Connection $database
    ) {}

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

    /**
     * @return Player[]
     */
    public function getOnlinePlayers(): array
    {
        $players = [];
        
        $data = $this->database->fetchAll('SELECT id, name FROM minecraft_player WHERE is_online = ?', 1);
        foreach ($data as $row) {
            $player = new Player();
            $player->id = $row['id'];
            $player->nickname = $row['name'];
            $players[] = $player;
        }
        
        return $players;
    }

    /**
     * @return PlayerVipActivation[]
     */
    public function getLatestVipActivations(int $limit = 5): array
    {
        $activations = [];

        $result = $this->database->query('SELECT minecraft_player.name as name, minecraft_rank.updated_at as updated_at FROM minecraft_rank JOIN minecraft_player ON minecraft_rank.player_id = minecraft_player.id WHERE minecraft_rank.expire_at > NOW() ORDER BY minecraft_rank.updated_at DESC LIMIT ?', $limit);

        foreach ($result as $row) {
            $activation = new PlayerVipActivation();
            $activation->nickname = $row['name'];
            $activation->activatedAt = DateTime::from($row['updated_at']);
            $activations[] = $activation;
        }

        return $activations;
    }
}
