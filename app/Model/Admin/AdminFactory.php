<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;

class AdminFactory
{
	private Passwords $passwords;

	public function __construct(Passwords $passwords)
	{
		$this->passwords = $passwords;
	}

	public function create(AdminData $adminData): Admin
	{
		return new Admin(Uuid::uuid4(), $adminData, $this->passwords);
	}
}
