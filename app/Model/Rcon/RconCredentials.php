<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

class RconCredentials
{
	private string $password;
	private string $proxyHost;
	private int $proxyRconPort;

	public function __construct(
		string $password, 
		string $proxyAddress, 
		int $proxyPort
	) {
		$this->password = $password;
		$this->proxyHost = $proxyAddress;
		$this->proxyRconPort = $proxyPort;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getProxyHost(): string
	{
		return $this->proxyHost;
	}

	public function getProxyRconPort(): int
	{
		return $this->proxyRconPort;
	}
}
