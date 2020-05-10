<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Minecord\Model\Admin\AdminProvider;
use Minecord\Model\Image\Image;
use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Nette\Http\FileUpload;

final class PageDataFactory
{
	private AdminProvider $adminProvider;
	private ImageDataFactory $imageDataFactory;
	private ImageFacade $imageFacade;

	public function __construct(
		AdminProvider $adminProvider,
		ImageDataFactory $imageDataFactory, 
		ImageFacade $imageFacade
	) {
		$this->adminProvider = $adminProvider;
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
		$data->author = $this->adminProvider->provide();

		return $data;
	}
}
