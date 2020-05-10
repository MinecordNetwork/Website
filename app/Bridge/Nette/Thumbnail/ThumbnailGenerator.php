<?php

declare(strict_types=1);

namespace Minecord\Bridge\Nette\Thumbnail;

use Nette\Utils\Image;
use Nette\Utils\ImageException;
use Nette\Utils\UnknownImageFileException;

class ThumbnailGenerator extends ThumbnailAbstractGenerator
{
	/**
	 * @throws ImageException
	 * @throws UnknownImageFileException
	 */
	protected function createThumb(): void
	{
		$image = Image::fromFile($this->src);
		$image->resize($this->width, $this->height, $this->crop ? Image::EXACT : Image::FIT);
		$image->save($this->desc);
	}
}
