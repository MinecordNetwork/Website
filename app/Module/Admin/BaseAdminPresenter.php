<?php

declare(strict_types=1);

namespace App\Module\Admin;

use App\Model\Article\ArticleFacade;
use App\Model\Session\SessionProvider;
use App\Model\User\Auth\UserAuthenticator;
use App\Model\User\User;
use App\Model\User\UserProvider;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Rixafy\Language\LanguageProvider;

/**
 * @property BaseAdminTemplate $template
 */
abstract class BaseAdminPresenter extends Presenter
{
    #[Inject] public UserAuthenticator $adminAuthenticator;
    #[Inject] public SessionProvider $sessionProvider;
    #[Inject] public LanguageProvider $languageProvider;
    #[Inject] public UserProvider $adminProvider;
    #[Inject] public ArticleFacade $articleFacade;

    protected ?User $user;

    public function startup(): void
    {
        parent::startup();

        $this->sessionProvider->setup();
        $this->languageProvider->setup(isset($_SERVER['SERVER_NAME']) && substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs');

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

        $javaScript = __DIR__ . '/../../../public/js/build/admin.js';
        $styleSheets = __DIR__ . '/../../../public/css/admin.css';

        if (file_exists($javaScript)) {
            $this->template->javaScriptTag = (string) filemtime($javaScript);
        }

        if (file_exists($styleSheets)) {
            $this->template->styleSheetsTag = (string) filemtime($styleSheets);
        }

        if ($this->isAjax() && $this->getRequest()->getMethod() !== 'POST' && $this->getParameter('do') === null) {
            $this->redrawControl('page');
            $this->redrawControl('sidebarArea');
            $this->redrawControl('sidebar');
            $this->redrawControl('title');
        }
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
