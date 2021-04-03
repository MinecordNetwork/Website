<?php

declare(strict_types=1);

namespace App\Module\Front;

use App\Model\Session\Session;
use App\Model\Session\SessionProvider;
use App\Model\System\System;
use App\Model\System\SystemProvider;
use App\Model\User\Auth\UserAuthenticator;
use App\Model\User\User;
use App\Model\User\UserProvider;
use Nette\Application\AbortException;
use Nette\Application\Helpers;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Rixafy\Currency\CurrencyProvider;
use Rixafy\Language\Language;
use Rixafy\Language\LanguageProvider;
use Tracy\Debugger;

/**
 * @property BaseFrontTemplate $template
 */
abstract class BaseFrontPresenter extends Presenter
{
    #[Inject] public SessionProvider $sessionProvider;
    #[Inject] public UserProvider $userProvider;
    #[Inject] public UserAuthenticator $userAuthenticator;
    #[Inject] public LanguageProvider $languageProvider;
    #[Inject] public CurrencyProvider $currencyProvider;
    #[Inject] public SystemProvider $systemProvider;

    protected System $system;
    protected Session $session;
    protected Language $language;
    protected ?User $user;

    public function startup()
    {
        parent::startup();

        $this->sessionProvider->setup();
        $this->languageProvider->setup(isset($_SERVER['SERVER_NAME']) && substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs');
        $this->currencyProvider->setup('EUR');
        
        $this->system = $this->systemProvider->provide();
        $this->session = $this->sessionProvider->provide();
        $this->user = $this->userProvider->provide();
        $this->language = $this->languageProvider->provide();
        
        if (!$this->session->isCrawler() && Debugger::$productionMode && $this->user === null) {
            if ($this->language->getIso() === 'cs' && !in_array($_SERVER['HTTP_CF_IPCOUNTRY'], ['CZ', 'SK'])) {
                $this->redirect('this', ['locale' => 'en']);
            } elseif ($this->language->getIso() === 'en' && in_array($_SERVER['HTTP_CF_IPCOUNTRY'], ['CZ', 'SK'])) {
                $this->redirect('this', ['locale' => 'cs']);
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
        $this->template->country = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'CZ';
        $this->template->currency = $this->currencyProvider->provide();
        $this->template->cssBundleVersion = filemtime(__DIR__ . '/../../../public/css/style.css');
        $this->template->jsBundleVersion = filemtime(__DIR__ . '/../../../public/js/build/bundle.js');
        
        if ($this->template->locale === 'cs') {
            $this->template->localeOther = 'en';
            $this->template->dateFormat = 'd.m.Y';
            $this->template->dateTimeFormat = 'd.m.Y H:i';
        } else {
            $this->template->localeOther = 'cs';
            $this->template->dateFormat = 'Y-m-d';
            $this->template->dateTimeFormat = 'Y-m-d H:i';
        }
    }

    /**
     * @throws AbortException
     */
    public function handleLogout(): void
    {
        $this->userAuthenticator->logOut();
        $this->redirect('Homepage:default');
    }
}
