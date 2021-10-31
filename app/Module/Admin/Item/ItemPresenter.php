<?php

declare(strict_types=1);

namespace App\Module\Admin\Item;

use App\Module\Admin\Item\Grid\ItemGridFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\ComponentModel\IComponent;

/**
 * @property ItemTemplate $template
 */
class ItemPresenter extends BaseAdminPresenter
{
    public function __construct(
        private ItemGridFactory $itemGridFactory
    ) {
        parent::__construct();
    }

    public function actionEdit(string $id): void
    {

    }

    public function createComponentGrid(): ?IComponent
    {
        return $this->itemGridFactory->create();
    }
}
