<?php

declare(strict_types=1);

namespace App\Module\Admin\Article;

use App\Model\Article\Article;
use App\Model\Article\ArticleDataFactory;
use App\Model\Image\Image;
use App\Model\Image\ImageDataFactory;
use App\Model\Image\ImageFacade;
use App\Model\Route\RouteProvider;
use App\Module\Admin\Article\Form\ArticleFormFactory;
use App\Module\Admin\Article\Form\ArticleThumbnailFormFactory;
use App\Module\Admin\Article\Grid\ArticleGridFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Http\FileUpload;
use Ramsey\Uuid\Uuid;

/**
 * @property ArticleTemplate $template
 */
class ArticlePresenter extends BaseAdminPresenter
{
    private ?Article $article = null;

    public function __construct(
        private ImageFacade $imageFacade,
        private ImageDataFactory $imageDataFactory,
        private ArticleFormFactory $articleFormFactory,
        private ArticleThumbnailFormFactory $articleThumbnailFormFactory,
        private ArticleGridFactory $articleGridFactory,
        private ArticleDataFactory $articleDataFactory,
        private RouteProvider $routeProvider
    ) {
        parent::__construct();
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
                $this->flashMessage('Príspevok bol úspešne vytvorený!', 'success');
                $this->redirect('this');
            } else {
                $this->articleFacade->edit($this->article->getId(), $this->articleDataFactory->createFromFormData($data));
                $this->flashMessage('Príspevok bol úspešne upravený!', 'success');
                $this->redrawControl('flashes');
            }
            $this->routeProvider->createRoutes('en');
            $this->routeProvider->createRoutes('cs');
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
                $prevImage === null ?? $this->imageFacade->remove($prevImage->getId());

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
        return $this->articleGridFactory->create();
    }
}
