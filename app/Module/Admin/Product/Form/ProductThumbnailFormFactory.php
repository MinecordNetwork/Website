<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product\Form;

use Minecord\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class ProductThumbnailFormFactory
{
	public function create(): Form
	{
		$form = new AdminForm();

		$form->addUpload('image', 'Image')
			->setHtmlAttribute('onChange', 'this.form.submit()')
			->setRequired();

		return $form;
	}
}
