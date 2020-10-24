<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Item;

use Minecord\Model\Image\ImageDataFactory;
use Minecord\Model\Image\ImageFacade;
use Minecord\Module\Admin\Item\Form\ItemFormFactory;
use Minecord\Module\Admin\Item\Grid\ItemGridFactory;
use Minecord\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Ramsey\Uuid\Uuid;

/**
 * @property ItemTemplate $template
 */
class ItemPresenter extends BaseAdminPresenter
{
	private ImageFacade $imageFacade;
	private ImageDataFactory $imageDataFactory;
	private ItemFormFactory $itemFormFactory;
	private ItemGridFactory $itemGridFactory;

	public function __construct(
		ImageFacade $imageFacade,
		ImageDataFactory $imageDataFactory,
		ItemFormFactory $itemFormFactory,
		ItemGridFactory $itemGridFactory
	) {
		parent::__construct();
		$this->imageFacade = $imageFacade;
		$this->imageDataFactory = $imageDataFactory;
		$this->itemFormFactory = $itemFormFactory;
		$this->itemGridFactory = $itemGridFactory;
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
