<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Auth;

use Minecord\Model\Admin\Exception\InvalidPasswordException;
use Minecord\Module\Admin\Auth\Form\LoginFormFactory;
use Minecord\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;

class AuthPresenter extends BaseAdminPresenter
{
	private LoginFormFactory $loginFormFactory;

	public function __construct(LoginFormFactory $loginFormFactory)
	{
		parent::__construct();
		$this->loginFormFactory = $loginFormFactory;
	}

	public function createComponentLoginForm(): ?IComponent
	{
		$form = $this->loginFormFactory->create();
		$form->getElementPrototype()->setAttribute('class', 'ajax');

		$form->onSuccess[] = function(Form $ignored, array $values): void {
			try {
				$this->adminAuthenticator->authenticate($values['email'], $values['password']);
				$this->flashMessage('Successfully logged in!', 'success');
				$this->redirect('Dashboard:default');

			} catch(InvalidPasswordException|InvalidPasswordException $e) {
				$this->flashMessage('Invalid credentials', 'danger');
			}
		};

		return $form;
	}
}
