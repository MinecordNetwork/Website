<?php

declare(strict_types=1);

namespace Minecord\Model\Session\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class SessionNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Session with id "' . $id . '" not found.');
	}

	public static function byHashAndDomainId(string $hash, UuidInterface $domainId): self
	{
		return new self('Session with hash "' . $hash . '" and domainId "' . $domainId . '" not found.');
	}

	public static function byIdAndDomainId(UuidInterface $id, UuidInterface $domainId)
	{
		return new self('Session with id "' . $id . '" and domainId "' . $domainId . '" not found.');
	}
}
