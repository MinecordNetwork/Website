<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Page\Form;

use Minecord\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class PageThumbnailFormFactory
{
	public function create(): Form
	{
		$form = new AdminForm();

		$form->addUpload('image', 'Obrázok')
			->setHtmlAttribute('onChange', 'this.form.submit()')
			->setRequired();

		return $form;
	}
}
