<?php

declare(strict_types=1);

namespace Minecord\Model\Article;

use Minecord\Model\Admin\Admin;

final class ArticleData
{
	public string $titleEnglish;
	public string $titleCzech;
	public string $contentEnglish;
	public string $contentCzech;
	public string $editorialEnglish;
	public string $editorialCzech;
	public Admin $author;
}
