<?php

declare(strict_types=1);

namespace Minecord\Model\Route;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Model\Page\PageFacade;
use Nette\Application\Routers\RouteList;
use Nette\Application\UI\Presenter;

class RouteProvider
{
	private ArticleFacade $articleFacade;
	private PageFacade $pageFacade;

	public function __construct(
		ArticleFacade $articleFacade,
		PageFacade $pageFacade
	) {
		$this->articleFacade = $articleFacade;
		$this->pageFacade = $pageFacade;
	}
	
	public function cacheRouters(): void
	{
		$enRouter = new RouteList('Front');
		$csRouter = new RouteList('Front');

		foreach ($this->articleFacade->getAll() as $article) {
			$enRouter->addRoute($article->getRouteEnglish(), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId()
			]);
			$csRouter->addRoute($article->getRouteCzech(), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId()
			]);
		}

		foreach ($this->pageFacade->getAll() as $page) {
			$enRouter->addRoute($page->getRouteEnglish(), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId()
			]);
			$csRouter->addRoute($page->getRouteCzech(), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId()
			]);
		}

		apcu_store('minecord_en_router', $enRouter, 3600 * 24);
		apcu_store('minecord_cs_router', $csRouter, 3600 * 24);
	}
	
	public function getDynamicRouteList(string $locale): RouteList
	{
		$routeList = apcu_fetch(sprintf('minecord_%s_router', $locale));
		
		if (!$routeList) {
			$this->cacheRouters();
			$routeList = apcu_fetch(sprintf('minecord_%s_router', $locale));
		}
		
		return $routeList;
	}
}
