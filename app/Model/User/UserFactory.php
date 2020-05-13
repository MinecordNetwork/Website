<?php

declare(strict_types=1);

namespace Minecord\Model\User;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;

class UserFactory
{
	private Passwords $passwords;

	public function __construct(Passwords $passwords)
	{
		$this->passwords = $passwords;
	}

	public function create(UserData $userData): User
	{
		return new User(Uuid::uuid4(), $userData, $this->passwords);
	}
}
