<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vote\Site;

class VoteSite
{
	private int $id;
	private string $name;
	private string $baseUrl;
	private string $nickFieldName;

	public function __construct(int $id, string $name, string $baseUrl, string $nickFieldName)
	{
		$this->id = $id;
		$this->name = $name;
		$this->baseUrl = $baseUrl;
		$this->nickFieldName = $nickFieldName;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getBaseUrl(): string
	{
		return $this->baseUrl;
	}

	public function getNickFieldName(): string
	{
		return $this->nickFieldName;
	}

	/**
	 * @return VoteSite[]
	 */
	public static function getAll(): array
	{
		$sites = [];
		
		$sites[] = new VoteSite(1, 'Craftlist.org', 'https://craftlist.org/minecord', 'nickname');
		$sites[] = new VoteSite(2, 'Planet Minecraft', 'https://www.planetminecraft.com/server/minecord-net-survival-skyblock-vanilla-creative-amp-minigames/vote/', 'username');
		$sites[] = new VoteSite(3, 'MC Server List', 'https://minecraft-server-list.com/server/400992/vote/', 'nickname');
		$sites[] = new VoteSite(4, 'Minecraft-MP', 'https://minecraft-mp.com/server/212135/vote/', 'username');
		
		return $sites;
	}
}
