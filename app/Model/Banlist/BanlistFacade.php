<?php

declare(strict_types=1);

namespace App\Model\Banlist;

use Nette\Database\Connection;
use Nette\Utils\DateTime;

class BanlistFacade
{
    public function __construct(
        private Connection $database
    ) {}

    public function getCount(): int
    {
        return (int) $this->database->query('SELECT id FROM minecraft_ban WHERE is_active = 1')->getRowCount();
    }

    /** @return Ban[] */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        $bans = [];
        
        $query = sprintf('
                SELECT minecraft_ban.*, 
                admin.name AS admin_name, 
                target.name AS target_name
                FROM minecraft_ban
                JOIN minecraft_player admin
                    ON minecraft_ban.admin_id = admin.id 
                JOIN minecraft_player target
                    ON minecraft_ban.target_id = target.id
                WHERE minecraft_ban.is_active = 1
                ORDER BY minecraft_ban.created_at DESC
                LIMIT %s, %s', $offset, $limit);

        $result = $this->database->query($query);
        
        foreach ($result as $row) {
            $ban = new Ban();
            $ban->id = $row->id;
            $ban->adminName = $row->admin_name;
            $ban->targetName = $row->target_name;
            $ban->reason = $row->reason;
            $ban->createdAt = DateTime::from($row->created_at);
            $ban->expireAt = DateTime::from($row->expire_at);
            $bans[] = $ban;
        }

        return $bans;
    }
}
