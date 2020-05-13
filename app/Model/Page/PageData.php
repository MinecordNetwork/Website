<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Minecord\Model\User\User;

final class PageData
{
	public string $titleEnglish;
	public string $titleCzech;
	public string $contentEnglish;
	public string $contentCzech;
	public User $author;
}
