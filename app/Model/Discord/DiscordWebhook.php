<?php

declare(strict_types=1);

namespace Minecord\Model\Discord;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Nette\Utils\Json;

class DiscordWebhook
{
	private string $url;

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function sendMessage(string $author, string $content): void
	{
		(new Client())->send(new Request('POST', $this->url, [
			'Content-type' => 'application/json'
		], Json::encode([
			'username' => $author,
			'content' => $content
		])));
	}
}
