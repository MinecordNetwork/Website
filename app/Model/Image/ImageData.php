<?php

declare(strict_types=1);

namespace Minecord\Model\Image;

class ImageData
{
	public string $originalName;
	public int $type = Image::TYPE_ARTICLE;
	public ?string $caption = null;
	public ?string $alternativeText = null;
}
