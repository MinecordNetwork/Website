<?php

declare(strict_types=1);

namespace App\Module\Console\System;

use App\Model\System\SystemData;
use App\Model\System\SystemFacade;
use PHPMinecraft\MinecraftQuery\Exception\MinecraftQueryException;
use PHPMinecraft\MinecraftQuery\MinecraftQueryResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SystemUpdateCommand extends Command
{
    /** @var string */
    public static $defaultName = 'system:update';

    public function __construct(
        private SystemFacade $systemFacade
    ) {
        parent::__construct();
    }
    
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = new SystemData();
        
        $data->discordCzechMemberCount = 0;
        $data->discordCzechMemberList = '';
        $discordData = @file_get_contents('https://discordapp.com/api/guilds/451685556343275521/widget.json');
        if ($discordData !== false) {
            $discordJson = json_decode($discordData);
            foreach ($discordJson->members as $member) {
                $data->discordCzechMemberList .= $member->username . ', ';
                $data->discordCzechMemberCount++;
            }
            $data->discordCzechMemberList = substr($data->discordCzechMemberList, 0, -2);
        }
        
        $data->discordEnglishMemberCount = 0;
        $data->discordEnglishMemberList = '';
        $discordData = @file_get_contents('https://discordapp.com/api/guilds/631481339735965696/widget.json');
        if ($discordData !== false) {
            $discordJson = json_decode($discordData);
            foreach ($discordJson->members as $member) {
                $data->discordEnglishMemberList .= $member->username . ', ';
                $data->discordEnglishMemberCount++;
            }
            $data->discordEnglishMemberList = utf8_encode(substr($data->discordEnglishMemberList, 0, -2));
        }

        try {
            $queryResult = MinecraftQueryResolver::fromAddress('mc.minecord.net')->getResult();
            $data->onlinePlayerCount = $queryResult->getOnlinePlayers();
            $data->onlinePlayerList = implode(', ', $queryResult->getPlayersSample());
        } catch (MinecraftQueryException) {}
        
        $this->systemFacade->edit($data);

        $output->writeln('<info>System data updated!</info>');

        return Command::SUCCESS;
    }
}
