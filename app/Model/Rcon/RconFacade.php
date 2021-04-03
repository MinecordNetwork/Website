<?php

declare(strict_types=1);

namespace App\Model\Rcon;

use App\Model\Rcon\WebSender\WebSender;
use Thedudeguy\Rcon;

class RconFacade
{
    public function __construct(
        private RconCredentials $rconCredentials
    ) {}

    public function sendCommands(array $commands, string $host, int $port): void
    {
        $rcon = new Rcon($host, $port, $this->rconCredentials->getPassword(), 3);
        
        if ($rcon->connect()) {
            foreach ($commands as $command) {
                $rcon->sendCommand($command);
            }
        }
        
        $rcon->disconnect();
    }
    
    public function sendCommandsToProxy(array $commands): void
    {
        $webSender = new WebSender($this->rconCredentials->getProxyHost(), $this->rconCredentials->getProxyRconPort(), $this->rconCredentials->getPassword());
        
        if ($webSender->connect()) {
            foreach ($commands as $command) {
                $webSender->sendCommand($command);
            }
        }
        
        $webSender->disconnect();
    }
}
