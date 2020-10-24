<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product;

use Minecord\Model\Product\Product;
use Minecord\Model\Product\ProductDataFactory;
use Minecord\Model\Product\ProductFacade;
use Minecord\Model\Image\Image;
use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Minecord\Model\Route\RouteProvider;
use Minecord\Module\Admin\Product\Form\ProductFormFactory;
use Minecord\Module\Admin\Product\Form\ProductThumbnailFormFactory;
use Minecord\Module\Admin\Product\Grid\ProductGridFactory;
use Minecord\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property ProductTemplate $template
 */
class ProductPresenter extends BaseAdminPresenter
{
	private ImageFacade $imageFacade;
	private ImageDataFactory $imageDataFactory;
	private ProductFormFactory $productFormFactory;
	private ProductThumbnailFormFactory $productThumbnailFormFactory;
	private ProductGridFactory $productGridFactory;
	private ProductDataFactory $productDataFactory;
	private ProductFacade $productFacade;
	private RouteProvider $routeProvider;
	private ?Product $product = null;

	public function __construct(
		ImageFacade $imageFacade,
		ImageDataFactory $imageDataFactory,
		ProductFormFactory $productFormFactory,
		ProductThumbnailFormFactory $productThumbnailFormFactory,
		ProductGridFactory $productGridFactory,
		ProductDataFactory $productDataFactory,
		ProductFacade $productFacade,
		RouteProvider $routeProvider
	) {
		parent::__construct();
		$this->imageFacade = $imageFacade;
		$this->imageDataFactory = $imageDataFactory;
		$this->productFormFactory = $productFormFactory;
		$this->productThumbnailFormFactory = $productThumbnailFormFactory;
		$this->productGridFactory = $productGridFactory;
		$this->productDataFactory = $productDataFactory;
		$this->productFacade = $productFacade;
		$this->routeProvider = $routeProvider;
	}

	public function actionEdit(string $id): void
	{
		$this->product = $this->productFacade->get(Uuid::fromString($id));
		$this->template->product = $this->product;
	}

	public function createComponentForm(): ?IComponent
	{
		$form = $this->productFormFactory->create($this->product);

		$form->onSuccess[] = function(Form $form, array $data): void {
			if ($this->product === null) {
				$this->productFacade->create($this->productDataFactory->createFromFormData($data));
				$this->flashMessage('Nový produkt bol vytvorený!', 'success');
				$this->redirect('this');
			} else {
				$this->productFacade->edit($this->product->getId(), $this->productDataFactory->createFromFormData($data));
				$this->flashMessage('Produkt bol upravený', 'success');
				$this->redrawControl('flashes');
			}
			$this->routeProvider->createRoutes('en');
			$this->routeProvider->createRoutes('cs');
		};

		return $form;
	}

	public function createComponentThumbnailForm(): Form
	{
		$form = $this->productThumbnailFormFactory->create();

		$form->onSuccess[] = function (Form $form, array $formData) {
			/** @var FileUpload $netteImage */
			$netteImage = $formData['image'];

			if ($netteImage->getError() === 0) {
				$imageData = $this->imageDataFactory->createFromFormData($formData, Image::TYPE_PRODUCT);
				$image = $this->imageFacade->create($imageData, function (string $saveDir) use ($netteImage) {
					$netteImage->move($saveDir);
				});

				$prevImage = $this->product->getThumbnail();
				$this->productFacade->changeThumbnail($this->product->getId(), $image);
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

	public function createComponentGrid(): ?IComponent
	{
		return $this->productGridFactory->create();
	}
}
