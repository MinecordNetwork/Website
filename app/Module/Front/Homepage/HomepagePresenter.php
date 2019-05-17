<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Homepage;

use Nette\Application\UI\Presenter;

class HomepagePresenter extends Presenter
{
    public function beforeRender()
    {
        $this->setLayout(__DIR__ . 'Templates/@layout.latte');
        $this->getTemplate()->setFile(__DIR__ . 'Templates/homepage.latte');
    }
}
