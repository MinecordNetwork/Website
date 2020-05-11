<?php

declare(strict_types=1);

namespace Minecord\Model\System;

use Ramsey\Uuid\Uuid;

final class SystemFactory
{
	public function create(SystemData $data): System
	{
		return new System(Uuid::uuid4(), $data);
	}
}
