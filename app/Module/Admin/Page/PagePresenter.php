<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Page;

use Minecord\Model\Page\Page;
use Minecord\Model\Page\PageDataFactory;
use Minecord\Model\Page\PageFacade;
use Minecord\Model\Image\Image;
use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Minecord\Model\Route\RouteProvider;
use Minecord\Module\Admin\Page\Form\PageFormFactory;
use Minecord\Module\Admin\Page\Form\PageThumbnailFormFactory;
use Minecord\Module\Admin\Page\Grid\PageGridFactory;
use Minecord\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property PageTemplate $template
 */
class PagePresenter extends BaseAdminPresenter
{
	private ImageFacade $imageFacade;
	private ImageDataFactory $imageDataFactory;
	private PageFormFactory $pageFormFactory;
	private PageThumbnailFormFactory $pageThumbnailFormFactory;
	private PageGridFactory $pageGridFactory;
	private PageDataFactory $pageDataFactory;
	private PageFacade $pageFacade;
	private RouteProvider $routeProvider;
	private ?Page $page = null;

	public function __construct(
		ImageFacade $imageFacade,
		ImageDataFactory $imageDataFactory,
		PageFormFactory $pageFormFactory,
		PageThumbnailFormFactory $pageThumbnailFormFactory,
		PageGridFactory $pageGridFactory,
		PageDataFactory $pageDataFactory,
		PageFacade $pageFacade, 
		RouteProvider $routeProvider
	) {
		parent::__construct();
		$this->imageFacade = $imageFacade;
		$this->imageDataFactory = $imageDataFactory;
		$this->pageFormFactory = $pageFormFactory;
		$this->pageThumbnailFormFactory = $pageThumbnailFormFactory;
		$this->pageGridFactory = $pageGridFactory;
		$this->pageDataFactory = $pageDataFactory;
		$this->pageFacade = $pageFacade;
		$this->routeProvider = $routeProvider;
	}


	public function actionEdit(string $id): void
	{
		$this->page = $this->pageFacade->get(Uuid::fromString($id));
		$this->template->page = $this->page;
	}

	public function createComponentForm(): ?IComponent
	{
		$form = $this->pageFormFactory->create($this->page);

		$form->onSuccess[] = function(Form $form, array $data): void {
			if ($this->page === null) {
				$this->pageFacade->create($this->pageDataFactory->createFromFormData($data));
				$this->flashMessage('New page successfully created!', 'success');
			} else {
				$this->pageFacade->edit($this->page->getId(), $this->pageDataFactory->createFromFormData($data));
			}
			$this->routeProvider->createRoutes('en');
			$this->routeProvider->createRoutes('cs');
			$this->redirect('this');
		};

		return $form;
	}

	public function createComponentThumbnailForm(): Form
	{
		$form = $this->pageThumbnailFormFactory->create();

		$form->onSuccess[] = function (Form $form, array $formData) {
			/** @var FileUpload $netteImage */
			$netteImage = $formData['image'];

			if ($netteImage->getError() === 0) {
				$imageData = $this->imageDataFactory->createFromFormData($formData, Image::TYPE_ARTICLE);
				$image = $this->imageFacade->create($imageData, function (string $saveDir) use ($netteImage) {
					$netteImage->move($saveDir);
				});

				$prevImage = $this->page->getThumbnail();
				$this->pageFacade->changeThumbnail($this->page->getId(), $image);
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
		return $this->pageGridFactory->create();
	}
}
