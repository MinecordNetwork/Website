<?php

declare(strict_types=1);

namespace App\Model\PlayerData;

use Nette\Database\Connection;

class PlayerDataFacade
{
    public function __construct(
        private Connection $database
    ) {}

    public function resetMonthlyStats(): void
    {
        $columns = [];
        
        $data = $this->database->fetch('SELECT * FROM minecraft_player_data LIMIT 1');
        if ($data !== null) {
            foreach ($data as $key => $value) {
                if (str_ends_with($key, '_monthly')) {
                    $columns[] = $key;
                }
            }
        }
        
        $queryBuilder = '';
        foreach ($columns as $column) {
            $queryBuilder .= $column . ' = 0, ';
        }
        $queryBuilder = substr($queryBuilder, 0, -2);
        
        $this->database->query('UPDATE minecraft_player_data SET ' . $queryBuilder);
    }
}
