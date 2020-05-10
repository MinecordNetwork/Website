<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Article;

use Minecord\Model\Article\Article;
use Minecord\Model\Article\ArticleDataFactory;
use Minecord\Model\Article\ArticleFacade;
use Minecord\Model\Image\Image;
use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Minecord\Model\Route\RouteProvider;
use Minecord\Module\Admin\Article\Form\ArticleFormFactory;
use Minecord\Module\Admin\Article\Form\ArticleThumbnailFormFactory;
use Minecord\Module\Admin\Article\Grid\ArticleGridFactory;
use Minecord\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property ArticleTemplate $template
 */
class ArticlePresenter extends BaseAdminPresenter
{
	private ImageFacade $imageFacade;
	private ImageDataFactory $imageDataFactory;
	private ArticleFormFactory $articleFormFactory;
	private ArticleThumbnailFormFactory $articleThumbnailFormFactory;
	private ArticleGridFactory $articleGridFactory;
	private ArticleDataFactory $articleDataFactory;
	private ArticleFacade $articleFacade;
	private RouteProvider $routeProvider;
	private ?Article $article = null;

	public function __construct(
		ImageFacade $imageFacade,
		ImageDataFactory $imageDataFactory,
		ArticleFormFactory $articleFormFactory,
		ArticleThumbnailFormFactory $articleThumbnailFormFactory,
		ArticleGridFactory $articleGridFactory,
		ArticleDataFactory $articleDataFactory,
		ArticleFacade $articleFacade, 
		RouteProvider $routeProvider
	) {
		parent::__construct();
		$this->imageFacade = $imageFacade;
		$this->imageDataFactory = $imageDataFactory;
		$this->articleFormFactory = $articleFormFactory;
		$this->articleThumbnailFormFactory = $articleThumbnailFormFactory;
		$this->articleGridFactory = $articleGridFactory;
		$this->articleDataFactory = $articleDataFactory;
		$this->articleFacade = $articleFacade;
		$this->routeProvider = $routeProvider;
	}


	public function actionEdit(string $id): void
	{
		$this->article = $this->articleFacade->get(Uuid::fromString($id));
		$this->template->article = $this->article;
	}

	public function createComponentForm(): ?IComponent
	{
		$form = $this->articleFormFactory->create($this->article);

		$form->onSuccess[] = function(Form $form, array $data): void {
			if ($this->article === null) {
				$this->articleFacade->create($this->articleDataFactory->createFromFormData($data));
				$this->flashMessage('New article successfully created!', 'success');
			} else {
				$this->articleFacade->edit($this->article->getId(), $this->articleDataFactory->createFromFormData($data));
			}
			$this->routeProvider->createRoutes('en');
			$this->routeProvider->createRoutes('cs');
			$this->redirect('this');
		};

		return $form;
	}

	public function createComponentThumbnailForm(): Form
	{
		$form = $this->articleThumbnailFormFactory->create();

		$form->onSuccess[] = function (Form $form, array $formData) {
			/** @var FileUpload $netteImage */
			$netteImage = $formData['image'];

			if ($netteImage->getError() === 0) {
				$imageData = $this->imageDataFactory->createFromFormData($formData, Image::TYPE_ARTICLE);
				$image = $this->imageFacade->create($imageData, function (string $saveDir) use ($netteImage) {
					$netteImage->move($saveDir);
				});

				$prevImage = $this->article->getThumbnail();
				$this->articleFacade->changeThumbnail($this->article->getId(), $image);
				$prevImage !== null ? $this->imageFacade->remove($prevImage->getId()) : null;

				$this->flashMessage('Thumbnail was uploaded.', 'success');
				$this->redirect('this');

			} else {
				$this->flashMessage('There was error during thumbnail upload.', 'error');
				$this->redirect('this');
			}
		};

		return $form;
	}

	public function createComponentPageGrid(): ?IComponent
	{
		return $this->articleGridFactory->create();
	}
}
