<?php

declare(strict_types=1);

namespace Minecord\Module\Api\Sms;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

class SmsPresenter extends Presenter
{
	public function actionDefault(): void
	{
		$this->getHttpResponse()->setCode(204);
		$this->sendResponse(new TextResponse('test'));
	}
	
	public function beforeRender()
	{
		exit;
	}
}
