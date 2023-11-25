<?php

declare(strict_types=1);

namespace App\Model\Server;

class Server
{
    public int $id;
    public string $name;
    public string $displayName;
    public string $gameType;
    public string $host;
    public int $port;
    public ?int $rconPort = null;
}
