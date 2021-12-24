<?php

declare(strict_types=1);

namespace App\Module\Front\OnlineMap;

use App\Module\Front\BaseFrontPresenter;
use Nette\Application\AbortException;

/**
 * @property OnlineMapTemplate $template
 */
class OnlineMapPresenter extends BaseFrontPresenter
{
    /**
     * @throws AbortException
     */
    public function actionSurvival(): void
    {
        $this->redirectUrl('https://survival.' . $this->getHttpRequest()->getUrl()->getHost());
    }
}
