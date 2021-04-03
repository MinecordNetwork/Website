<?php

declare(strict_types=1);

namespace App\Module\Front\Homepage;

use App\Model\Article\ArticleFacade;
use App\Module\Front\BaseFrontPresenter;

/** 
 * @property-read HomepageTemplate $template
 */
class HomepagePresenter extends BaseFrontPresenter
{
    public function __construct(
        private ArticleFacade $articleFacade
    ) {
        parent::__construct();
    }

    public function actionDefault(): void
    {
        $this->template->articles = $this->articleFacade->getAllOrderedByCreatedAt();
    }
}
