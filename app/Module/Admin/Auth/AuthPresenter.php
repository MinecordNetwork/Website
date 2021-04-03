<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth;

use App\Model\User\Exception\InvalidPasswordException;
use App\Model\User\Exception\UserNotFoundException;
use App\Module\Admin\Auth\Form\LoginFormFactory;
use App\Module\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;

class AuthPresenter extends BaseAdminPresenter
{
    public function __construct(
        private LoginFormFactory $loginFormFactory
    ) {
        parent::__construct();
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

            } catch(InvalidPasswordException|UserNotFoundException) {
                $this->flashMessage('Invalid credentials', 'danger');
            }
        };

        return $form;
    }
}
