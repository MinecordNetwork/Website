<?php

declare(strict_types=1);

namespace App\Module\Admin\Item;

use App\Model\Image\ImageDataFactory;
use App\Model\Image\ImageFacade;
use App\Module\Admin\Item\Form\ItemFormFactory;
use App\Module\Admin\Item\Grid\ItemGridFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\ComponentModel\IComponent;
use Ramsey\Uuid\Uuid;

/**
 * @property ItemTemplate $template
 */
class ItemPresenter extends BaseAdminPresenter
{
    public function __construct(
        private ImageFacade $imageFacade,
        private ImageDataFactory $imageDataFactory,
        private ItemFormFactory $itemFormFactory,
        private ItemGridFactory $itemGridFactory
    ) {
        parent::__construct();
    }

    public function actionEdit(string $id): void
    {
        $this->item = $this->itemFacade->get(Uuid::fromString($id));
        $this->template->item = $this->item;
    }

    /*public function createComponentForm(): ?IComponent
    {
        $form = $this->itemFormFactory->create($this->item);

        $form->onSuccess[] = function(Form $form, array $data): void {
            if ($this->item === null) {
                $this->itemFacade->create($this->itemDataFactory->createFromFormData($data));
                $this->flashMessage('Nový produkt bol vytvorený!', 'success');
                $this->redirect('this');
            } else {
                $this->itemFacade->edit($this->item->getId(), $this->itemDataFactory->createFromFormData($data));
                $this->flashMessage('Produkt bol upravený', 'success');
                $this->redrawControl('flashes');
            }
            $this->routeProvider->createRoutes('en');
            $this->routeProvider->createRoutes('cs');
        };

        return $form;
    }*/

    public function createComponentGrid(): ?IComponent
    {
        return $this->itemGridFactory->create();
    }
}
