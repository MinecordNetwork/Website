<?php

declare(strict_types=1);

namespace App\Module\Admin\Page;

use App\Model\Page\Page;
use App\Model\Page\PageDataFactory;
use App\Model\Page\PageFacade;
use App\Model\Image\Image;
use App\Model\Image\ImageDataFactory;
use App\Model\Image\ImageFacade;
use App\Model\Route\RouteProvider;
use App\Module\Admin\Page\Form\PageFormFactory;
use App\Module\Admin\Page\Form\PageThumbnailFormFactory;
use App\Module\Admin\Page\Grid\PageGridFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property PageTemplate $template
 */
class PagePresenter extends BaseAdminPresenter
{
    private ?Page $page = null;

    public function __construct(
        private ImageFacade $imageFacade,
        private ImageDataFactory $imageDataFactory,
        private PageFormFactory $pageFormFactory,
        private PageThumbnailFormFactory $pageThumbnailFormFactory,
        private PageGridFactory $pageGridFactory,
        private PageDataFactory $pageDataFactory,
        private PageFacade $pageFacade,
        private RouteProvider $routeProvider
    ) {
        parent::__construct();
    }
    
    public function actionEdit(string $id): void
    {
        $this->page = $this->pageFacade->get(Uuid::fromString($id));
        $this->template->page = $this->page;
    }

    public function createComponentForm(): ?IComponent
    {
        $form = $this->pageFormFactory->create($this->page);

        $form->onSuccess[] = function(array $data): void {
            if ($this->page === null) {
                $this->pageFacade->create($this->pageDataFactory->createFromFormData($data));
                $this->flashMessage('Stránka úspešne vytvorená!', 'success');
                $this->redirect('this');
            } else {
                $this->pageFacade->edit($this->page->getId(), $this->pageDataFactory->createFromFormData($data));
                $this->flashMessage('Stránka úspešne upravená!', 'success');
                $this->redrawControl('flashes');
            }
            $this->routeProvider->createRoutes('en');
            $this->routeProvider->createRoutes('cs');
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
                $imageData = $this->imageDataFactory->createFromFormData($formData, Image::TYPE_PAGE);
                $image = $this->imageFacade->create($imageData, function (string $saveDir) use ($netteImage) {
                    $netteImage->move($saveDir);
                });

                $prevImage = $this->page->getThumbnail();
                $this->pageFacade->changeThumbnail($this->page->getId(), $image);
                $prevImage === null ?: $this->imageFacade->remove($prevImage->getId());

                $this->flashMessage('Thumbnail was uploaded.', 'success');

            } else {
                $this->flashMessage('There was error during thumbnail upload.', 'error');
            }
            
            $this->redirect('this');
        };

        return $form;
    }

    public function createComponentGrid(): ?IComponent
    {
        return $this->pageGridFactory->create();
    }
}
