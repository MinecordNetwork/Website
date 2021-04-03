<?php

declare(strict_types=1);

namespace App\Module\Front\Page;

use App\Model\Page\PageFacade;
use App\Module\Front\BaseFrontPresenter;
use Ramsey\Uuid\Uuid;

/**
 * @property PageTemplate $template
 */
class PagePresenter extends BaseFrontPresenter
{
    public function __construct(
        private PageFacade $pageFacade
    ) {
        parent::__construct();
    }

    public function actionDefault(string $id): void
    {
        $this->template->page = $this->pageFacade->get(Uuid::fromString($id));
    }
}
