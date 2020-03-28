<?php

declare(strict_types=1);

namespace Minecord\Model\Admin\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

final class AdminNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Admin with id "' . $id . '" not found.');
	}

	public static function byEmail(string $email): self
	{
		return new self('Admin with email "' . $email . '" not found.');
	}
}
