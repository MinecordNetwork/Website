<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Page\Form;

use Archette\FormUtils\Mapper\FormData;
use Minecord\Model\Page\Page;
use Minecord\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class PageFormFactory
{
	public function create(Page $page = null): Form
	{
		$form = new AdminForm();
		
		if ($page !== null) {
			$form->addText('id', 'ID')
				->setDefaultValue((string) $page->getId())
				->setHtmlAttribute('readonly', true);
		}
		
		$form->addText('titleEnglish', 'Title 🇺🇸')
			->setRequired();
		
		$form->addTextEditor('contentEnglish', 'Content 🇺🇸')
			->setRequired();

		$form->addText('titleCzech', 'Title 🇨🇿')
			->setRequired();
		
		$form->addTextEditor('contentCzech', 'Content 🇨🇿')
			->setRequired();

		$form->addSubmit('submit', 'Save page');
		
		if ($page !== null) {
			$form->setDefaults(new FormData($page->getData()));
		}
		
		return $form;
	}
}
