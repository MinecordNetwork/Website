<?php

declare(strict_types=1);

namespace Minecord\Module\Admin;

use Minecord\Model\Admin\Admin;
use Minecord\Model\Admin\AdminProvider;
use Minecord\Model\Admin\Auth\AdminAuthenticator;
use Minecord\Model\Session\SessionProvider;
use Nette\Application\UI\Presenter;

abstract class BaseAdminPresenter extends Presenter
{
	/** @inject */
	public AdminAuthenticator $adminAuthenticator;

	/** @inject */
	public SessionProvider $sessionProvider;

	/** @inject */
	public AdminProvider $adminProvider;

	protected ?Admin $admin;

	public function startup(): void
	{
		parent::startup();

		$this->sessionProvider->setup();
		
		$this->admin = $this->adminProvider->provide();	
		
		if ($this->admin !== null) {
			if ($this->getName() === 'Admin:Auth' && $this->getAction() === 'default') {
				$this->redirect('Dashboard:default');
			}

		} elseif ($this->getName() !== 'Admin:Auth' || $this->getAction() !== 'default') {
			$this->redirect('Auth:default');
		}
	}

	public function beforeRender(): void
	{
		$this->setLayout(__DIR__ . '/@Templates/@Layout/layout.latte');
		$this->getTemplate()->setFile(__DIR__ . '/@Templates/' . str_replace('Admin:', '', $this->getName()) . '/' . $this->getAction() .'.latte');
		$this->template->admin = $this->admin;
	}

	public function handleLogout(): void
	{
		$this->adminAuthenticator->logOut();
		$this->redirect('Dashboard:default');
	}
}
