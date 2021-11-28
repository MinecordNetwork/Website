<?php

declare(strict_types=1);

namespace App\Module\Front\Homepage;

use App\Model\Article\Article;
use App\Model\Article\ArticleFacade;
use App\Module\Front\BaseFrontPresenter;
use Nette\Application\BadRequestException;
use Nette\Utils\Paginator;

/** 
 * @property HomepageTemplate $template
 */
class HomepagePresenter extends BaseFrontPresenter
{
    /** @var Article[] */
    private array $articles;
    private Paginator $paginator;
    
    public function __construct(
        private ArticleFacade $articleFacade,
    ) {
        parent::__construct();
    }

    /**
     * @throws BadRequestException
     */
    public function actionDefault(int $page = 1): void
    {
        $this->paginator = new Paginator();
        $this->paginator->setItemCount($this->articleFacade->getCount());
        $this->paginator->setItemsPerPage(3);
        
        if ($page > $this->paginator->getLastPage() || $page <= 0) {
            throw new BadRequestException();
            
        } elseif ($page >= 1) {
            $this->paginator->setPage($page);
        }
        
        $this->articles = $this->articleFacade->getAllOrderedByCreatedAt(
            $this->paginator->getItemsPerPage(),
            $this->paginator->getOffset()
        );
    }

    public function renderDefault(): void
    {
        $this->template->paginator = $this->paginator;
        $this->template->articles = $this->articles;
    }
}
