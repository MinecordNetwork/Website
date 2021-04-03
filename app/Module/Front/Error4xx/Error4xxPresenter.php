<?php

declare(strict_types=1);

namespace App\Module\Front\Error4xx;

use Nette;
use Nette\Application\AbortException;
use Nette\Localization\Translator;

final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Translator $translator
    ) {
        parent::__construct();
    }

    public function startup(): void
    {
        parent::startup();
        if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
            $this->error();
        }
    }

    /**
     * @throws AbortException
     */
    public function renderDefault(Nette\Application\BadRequestException $exception): void
    {
        if ($exception->getCode() === 404) {
            $this->flashMessage($this->translator->translate('common.pageNotFound'), 'error');
            $this->redirectUrl('/');
        }

        $file = __DIR__ . '/../@Templates/Error/' . $exception->getCode() . '.latte';
        $this->template->setFile(is_file($file) ? $file : __DIR__ . '/../@Templates/Error/4xx.latte');
    }
}
