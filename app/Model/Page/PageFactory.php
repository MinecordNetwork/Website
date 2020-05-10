<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Ramsey\Uuid\Uuid;

final class PageFactory
{
	public function create(PageData $data): Page
	{
		return new Page(Uuid::uuid4(), $data);
	}
}
