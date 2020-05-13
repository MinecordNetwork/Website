<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Minecord\Model\User\UserProvider;
use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;

final class PageDataFactory
{
	private UserProvider $userProvider;
	private ImageDataFactory $imageDataFactory;
	private ImageFacade $imageFacade;

	public function __construct(
		UserProvider $userProvider,
		ImageDataFactory $imageDataFactory, 
		ImageFacade $imageFacade
	) {
		$this->userProvider = $userProvider;
		$this->imageDataFactory = $imageDataFactory;
		$this->imageFacade = $imageFacade;
	}

	public function createFromFormData(array $formData): PageData
	{
		$data = new PageData();
		$data->titleEnglish = $formData['titleEnglish'];
		$data->titleCzech = $formData['titleCzech'];
		$data->contentEnglish = $formData['contentEnglish'];
		$data->contentCzech = $formData['contentCzech'];
		$data->author = $this->userProvider->provide();

		return $data;
	}
}
