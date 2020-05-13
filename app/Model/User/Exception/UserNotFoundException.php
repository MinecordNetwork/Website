<?php

declare(strict_types=1);

namespace Minecord\Model\User\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

final class UserNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('User with id "' . $id . '" not found.');
	}

	public static function byEmail(string $email): self
	{
		return new self('User with email "' . $email . '" not found.');
	}
}
