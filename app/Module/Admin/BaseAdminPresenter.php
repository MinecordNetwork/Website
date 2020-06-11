<?php

declare(strict_types=1);

namespace Minecord\Module\Admin;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Model\Session\SessionProvider;
use Minecord\Model\User\Auth\UserAuthenticator;
use Minecord\Model\User\User;
use Minecord\Model\User\UserProvider;
use Nette\Application\UI\Presenter;

/**
 * @property BaseAdminTemplate $template
 */
abstract class BaseAdminPresenter extends Presenter
{
	/** @inject */
	public UserAuthenticator $adminAuthenticator;

	/** @inject */
	public SessionProvider $sessionProvider;

	/** @inject */
	public UserProvider $adminProvider;

	/** @inject */
	public ArticleFacade $articleFacade;

	protected ?User $user;

	public function startup(): void
	{
		parent::startup();

		$this->sessionProvider->setup();
		
		$this->user = $this->adminProvider->provide();	
		
		if ($this->user !== null) {
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
		$this->template->user = $this->user;
		$this->template->isAuthPresenter = $this->getName() === 'Admin:Auth';
	}

	public function handleLogout(): void
	{
		$this->adminAuthenticator->logOut();
		$this->redirect('Dashboard:default');
	}

	public function handleNotifyDiscord(): void
	{
		$this->articleFacade->notifyDiscord();
	}
}
