<?php

declare(strict_types=1);

namespace Minecord\Module\Console\System;

use Minecord\Model\System\SystemData;
use Minecord\Model\System\SystemFacade;
use Minecord\Model\System\SystemProvider;
use PHPMinecraft\MinecraftQuery\MinecraftQueryResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SystemUpdateCommand extends Command
{
	/** @var string */
	public static $defaultName = 'system:update';

	private SystemProvider $systemProvider;
	private SystemFacade $systemFacade;

	public function __construct(
		SystemProvider $systemProvider, 
		SystemFacade $systemFacade
	) {
		parent::__construct();
		$this->systemProvider = $systemProvider;
		$this->systemFacade = $systemFacade;
	}

	public function execute(InputInterface $input, OutputInterface $output): int
	{
		$data = new SystemData();
		
		$data->discordCzechMemberCount = 0;
		$data->discordCzechMemberList = '';
		$discordJson = json_decode(file_get_contents('https://discordapp.com/api/guilds/451685556343275521/widget.json'));
		foreach ($discordJson->members as $member) {
			$data->discordCzechMemberList .= $member->username . ', ';
			$data->discordCzechMemberCount++;
		}
		$data->discordCzechMemberList = substr($data->discordCzechMemberList, 0, -2);
		
		$data->discordEnglishMemberCount = 0;
		$data->discordEnglishMemberList = '';
		$discordJson = json_decode(file_get_contents('https://discordapp.com/api/guilds/631481339735965696/widget.json'));
		foreach ($discordJson->members as $member) {
			$data->discordEnglishMemberList .= $member->username . ', ';
			$data->discordEnglishMemberCount++;
		}
		$data->discordEnglishMemberList = utf8_encode(substr($data->discordEnglishMemberList, 0, -2));

		$queryResult = (MinecraftQueryResolver::fromAddress('mc.minecord.net'))->getResult();
		$data->onlinePlayerCount = $queryResult->getOnlinePlayers();
		$data->onlinePlayerList = implode(', ', $queryResult->getPlayersSample());
		
		$this->systemFacade->edit($data);

		$output->writeln('<info>System data updated!</info>');

		return 1;
	}
}
