<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Admin\Admin;
use Minecord\Model\Admin\AdminProvider;
use Minecord\Model\Admin\Auth\AdminAuthenticator;
use Minecord\Model\Session\Session;
use Minecord\Model\Session\SessionProvider;
use Minecord\Model\System\System;
use Minecord\Model\System\SystemData;
use Minecord\Model\System\SystemFacade;
use Minecord\Model\System\SystemProvider;
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
	
	/** @inject */
	public SystemProvider $systemProvider;

	protected System $system;
	protected Session $session;
	protected Language $language;
	protected ?Admin $admin;

	public function startup()
	{
		parent::startup();

		$this->sessionProvider->setup();
		$this->languageProvider->setup(isset($_SERVER['SERVER_NAME']) && substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs');
		
		$this->system = $this->systemProvider->provide();
		$this->session = $this->sessionProvider->provide();
		$this->admin = $this->adminProvider->provide();
		$this->language = $this->languageProvider->provide();
	}

	public function beforeRender(): void
	{
		$this->setLayout(__DIR__ . '/@Templates/@Layout/layout.latte');
		$this->getTemplate()->setFile(__DIR__ . '/@Templates/' . Helpers::splitName($this->getName())[1] . '/' . $this->getAction() .'.latte');
		$this->template->system = $this->system;
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
