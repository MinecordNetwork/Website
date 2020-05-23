<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product\Form;

use Archette\FormUtils\Mapper\FormData;
use Minecord\Model\Product\Product;
use Minecord\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class ProductFormFactory
{
	public function create(Product $product = null): Form
	{
		$form = new AdminForm();

		if ($product !== null) {
			$form->addText('id', 'ID')
				->setDefaultValue((string) $product->getId())
				->setHtmlAttribute('readonly', true);
		}

		$form->addText('nameEnglish', 'Name 🇺🇸')
			->setRequired();

		$form->addTextEditor('descriptionEnglish', 'Description 🇺🇸')
			->setRequired();

		$form->addText('nameCzech', 'Name 🇨🇿')
			->setRequired();

		$form->addTextEditor('descriptionCzech', 'Description 🇨🇿')
			->setRequired();

		$form->addText('smsCode', 'SMS Code')
			->setRequired();

		$form->addPrice('price', 'Price')
			->setRequired();

		$form->addPrice('priceCzechSms', 'Price SMS CZ')
			->setRequired();

		$form->addPrice('priceSlovakSms', 'Price SMS SK')
			->setRequired();

		$form->addCheckbox('isRank', 'Is this product a Rank?');

		$form->addSubmit('submit', 'Save product');

		if ($product !== null) {
			$form->setDefaults(new FormData($product->getData()));
		}

		return $form;
	}
}
