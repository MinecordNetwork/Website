<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Auth\Form;

use Nette\Application\UI\Form;

class LoginFormFactory
{
	public function create(): Form
	{
		$form = new Form();

		$form->addEmail('email')
			->setRequired();

		$form->addPassword('password')
			->setRequired();

		$form->addSubmit('submit')
			->setHtmlAttribute('class', 'ajax');

		return $form;
	}
}