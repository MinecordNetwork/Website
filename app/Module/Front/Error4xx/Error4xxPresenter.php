<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Error4xx;

use Nette;
use Nette\Localization\ITranslator;

final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
	/** @inject */
	public ITranslator $translator;

	public function startup(): void
	{
		parent::startup();
		if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
			$this->error();
		}
	}

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
