<?php

declare(strict_types=1);

namespace Minecord\Model\System;

final class SystemData
{
	public int $onlinePlayerCount = 0;
	public string $onlinePlayerList;
	public int $discordEnglishMemberCount = 0;
	public string $discordEnglishMemberList;
	public int $discordCzechMemberCount = 0;
	public string $discordCzechMemberList;
}
