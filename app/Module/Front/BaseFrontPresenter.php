<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Session\Session;
use Minecord\Model\Session\SessionProvider;
use Minecord\Model\System\System;
use Minecord\Model\System\SystemProvider;
use Minecord\Model\User\Auth\UserAuthenticator;
use Minecord\Model\User\User;
use Minecord\Model\User\UserProvider;
use Nette\Application\Helpers;
use Nette\Application\UI\Presenter;
use Rixafy\Language\Language;
use Rixafy\Language\LanguageProvider;
use Tracy\Debugger;

/**
 * @property BaseFrontTemplate $template
 */
abstract class BaseFrontPresenter extends Presenter
{
	/** @inject */
	public SessionProvider $sessionProvider;
	
	/** @inject */
	public UserProvider $userProvider;
	
	/** @inject */
	public UserAuthenticator $userAuthenticator;
	
	/** @inject */
	public LanguageProvider $languageProvider;
	
	/** @inject */
	public SystemProvider $systemProvider;

	protected System $system;
	protected Session $session;
	protected Language $language;
	protected ?User $user;

	public function startup()
	{
		parent::startup();

		$this->sessionProvider->setup();
		$this->languageProvider->setup(isset($_SERVER['SERVER_NAME']) && substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs');
		
		$this->system = $this->systemProvider->provide();
		$this->session = $this->sessionProvider->provide();
		$this->user = $this->userProvider->provide();
		$this->language = $this->languageProvider->provide();
		
		if (isset($_SERVER['HTTP_USER_AGENT']) && !preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT']) && Debugger::$productionMode && $this->user === null) {
			if ($this->language->getIso() === 'cs' && !in_array($_SERVER['HTTP_CF_IPCOUNTRY'], ['CZ', 'SK'])) {
				$this->redirectUrl('https://minecord.net' . $_SERVER['REQUEST_URI']);
			} elseif ($this->language->getIso() === 'en' && in_array($_SERVER['HTTP_CF_IPCOUNTRY'], ['CZ', 'SK'])) {
				$this->redirectUrl('https://minecord.cz' . $_SERVER['REQUEST_URI']);
			}
		}
	}

	public function beforeRender(): void
	{
		$this->setLayout(__DIR__ . '/@Templates/@Layout/layout.latte');
		$this->getTemplate()->setFile(__DIR__ . '/@Templates/' . Helpers::splitName($this->getName())[1] . '/' . $this->getAction() .'.latte');
		$this->template->system = $this->system;
		$this->template->user = $this->user;
		$this->template->locale = $this->language->getIso();
		$this->template->country = $_SERVER['HTTP_CF_IPCOUNTRY'];
		$this->template->assetVersion = filemtime(__DIR__ . '/../../../public/css/style.css');
		
		if ($this->template->locale === 'cs') {
			$this->template->dateFormat = 'd.m.Y';
			$this->template->dateTimeFormat = 'd.m.Y H:i';
		} else {
			$this->template->dateFormat = 'Y-m-d';
			$this->template->dateTimeFormat = 'Y-m-d H:i';
		}
	}

	public function handleLogout(): void
	{
		$this->userAuthenticator->logOut();
		$this->redirect('Homepage:default');
	}
}
