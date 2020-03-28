<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use Minecord\Model\Session\SessionProvider;

class AdminProvider
{
	private SessionProvider $sessionProvider;

	public function __construct(SessionProvider $sessionProvider)
	{
		$this->sessionProvider = $sessionProvider;
	}

	public function provide(): ?Admin
	{
		return $this->sessionProvider->provide()->getAdmin();
	}
}
