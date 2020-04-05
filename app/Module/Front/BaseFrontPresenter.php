<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Admin\Admin;
use Minecord\Model\Admin\AdminProvider;
use Minecord\Model\Admin\Auth\AdminAuthenticator;
use Minecord\Model\Session\Session;
use Minecord\Model\Session\SessionProvider;
use Nette\Application\Helpers;
use Nette\Application\UI\Presenter;
use Rixafy\Language\Language;
use Rixafy\Language\LanguageProvider;

/**
 * @property BaseFrontTemplate $template
 */
abstract class BaseFrontPresenter extends Presenter
{
	/** @inject */
	public SessionProvider $sessionProvider;
	
	/** @inject */
	public AdminProvider $adminProvider;
	
	/** @inject */
	public AdminAuthenticator $adminAuthenticator;
	
	/** @inject */
	public LanguageProvider $languageProvider;

	protected Session $session;
	protected ?Admin $admin;
	protected Language $language;

	public function startup()
	{
		parent::startup();

		$this->sessionProvider->setup();
		$this->languageProvider->setup('en');
		
		$this->session = $this->sessionProvider->provide();
		$this->admin = $this->adminProvider->provide();
		$this->language = $this->languageProvider->provide();
	}

	public function beforeRender(): void
	{
		$this->setLayout(__DIR__ . '/@Templates/@Layout/layout.latte');
		$this->getTemplate()->setFile(__DIR__ . '/@Templates/' . Helpers::splitName($this->getName())[1] . '/' . $this->getAction() .'.latte');
		$this->template->admin = $this->admin;
		$this->template->locale = $this->language->getIso();
		$this->template->assetVersion = filemtime(__DIR__ . '/../../../public/css/style.css');
	}

	public function handleLogout(): void
	{
		$this->adminAuthenticator->logOut();
		$this->redirect('Homepage:default');
	}
}