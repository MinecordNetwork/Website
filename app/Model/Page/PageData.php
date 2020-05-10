<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Minecord\Model\Admin\Admin;

final class PageData
{
	public string $titleEnglish;
	public string $titleCzech;
	public string $contentEnglish;
	public string $contentCzech;
	public Admin $author;
}
