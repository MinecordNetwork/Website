<?php

declare(strict_types=1);

namespace Minecord\Model\Article;

use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Minecord\Model\User\UserProvider;

final class ArticleDataFactory
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

	public function createFromFormData(array $formData): ArticleData
	{
		$data = new ArticleData();
		$data->titleEnglish = $formData['titleEnglish'];
		$data->titleCzech = $formData['titleCzech'];
		$data->contentEnglish = $formData['contentEnglish'];
		$data->contentCzech = $formData['contentCzech'];
		$data->editorialEnglish = $formData['editorialEnglish'];
		$data->editorialCzech = $formData['editorialCzech'];
		$data->author = $this->userProvider->provide();

		return $data;
	}
}
