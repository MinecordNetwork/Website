<?php

declare(strict_types=1);

namespace App\Model\Item;

use Nette\Database\Connection;

final class ItemFacade
{
    public function __construct(
        private Connection $database,
    ) {}
    
    public function getItem(int $id): Item
    {
        $result = $this->database->query('SELECT * FROM minecraft_item WHERE id = ?', $id)->fetch();
        
        $item = new Item();
        $item->id = $result->id;
        $item->name = $result->name;
        
        return $item;
    }
}
