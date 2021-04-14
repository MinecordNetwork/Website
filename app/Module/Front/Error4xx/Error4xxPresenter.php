<?php

declare(strict_types=1);

namespace App\Module\Front\Error4xx;

use Nette;

final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @throws Nette\Application\BadRequestException
     */
    public function startup(): void
    {
        parent::startup();
        if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
            throw new Nette\Application\BadRequestException();
        }
    }

    public function renderDefault(Nette\Application\BadRequestException $exception): void
    {
        $file = __DIR__ . '/../@Templates/Error/' . $exception->getCode() . '.latte';
        $this->template->setFile(is_file($file) ? $file : __DIR__ . '/../@Templates/Error/4xx.latte');
    }
}
