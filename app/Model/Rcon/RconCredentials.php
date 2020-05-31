<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

class RconCredentials
{
	private string $password;

	public function __construct(string $password)
	{
		$this->password = $password;
	}

	public function getPassword(): string
	{
		return $this->password;
	}
}
