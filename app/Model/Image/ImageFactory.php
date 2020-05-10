<?php

declare(strict_types=1);

namespace Minecord\Model\Image;

use Ramsey\Uuid\Uuid;

class ImageFactory
{
	public function create(ImageData $imageData): Image
	{
		return new Image(Uuid::uuid4(), $imageData);
	}
}
