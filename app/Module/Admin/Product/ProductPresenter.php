<?php

declare(strict_types=1);

namespace App\Module\Admin\Product;

use App\Model\Product\Product;
use App\Model\Product\ProductDataFactory;
use App\Model\Product\ProductFacade;
use App\Model\Image\Image;
use App\Model\Image\ImageDataFactory;
use App\Model\Image\ImageFacade;
use App\Model\Route\RouteProvider;
use App\Module\Admin\Product\Form\ProductFormFactory;
use App\Module\Admin\Product\Form\ProductThumbnailFormFactory;
use App\Module\Admin\Product\Grid\ProductGridFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property ProductTemplate $template
 */
class ProductPresenter extends BaseAdminPresenter
{
    private ?Product $product = null;

    public function __construct(
        private ImageFacade $imageFacade,
        private ImageDataFactory $imageDataFactory,
        private ProductFormFactory $productFormFactory,
        private ProductThumbnailFormFactory $productThumbnailFormFactory,
        private ProductGridFactory $productGridFactory,
        private ProductDataFactory $productDataFactory,
        private ProductFacade $productFacade,
        private RouteProvider $routeProvider
    ) {
        parent::__construct();
    }

    public function actionEdit(string $id): void
    {
        $this->product = $this->productFacade->get(Uuid::fromString($id));
        $this->template->product = $this->product;
    }

    public function createComponentForm(): ?IComponent
    {
        $form = $this->productFormFactory->create($this->product);

        $form->onSuccess[] = function(array $data): void {
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

        $form->onSuccess[] = function (array $data): void {
            /** @var FileUpload $netteImage */
            $netteImage = $data['image'];

            if ($netteImage->getError() === 0) {
                $imageData = $this->imageDataFactory->createFromFormData($data, Image::TYPE_PRODUCT);
                $image = $this->imageFacade->create($imageData, function (string $saveDir) use ($netteImage) {
                    $netteImage->move($saveDir);
                });

                $prevImage = $this->product->getThumbnail();
                $this->productFacade->changeThumbnail($this->product->getId(), $image);
                $prevImage === null ?: $this->imageFacade->remove($prevImage->getId());

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
