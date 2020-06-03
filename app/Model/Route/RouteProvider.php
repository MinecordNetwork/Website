<?php

declare(strict_types=1);

namespace Minecord\Model\Route;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Model\Domain\Info\DomainInfoProvider;
use Minecord\Model\Page\PageFacade;
use Nette\Application\Routers\RouteList;
use Nette\Application\UI\Presenter;

class RouteProvider
{
	private ArticleFacade $articleFacade;
	private PageFacade $pageFacade;
	private DomainInfoProvider $domainInfoProvider;

	public function __construct(
		ArticleFacade $articleFacade,
		PageFacade $pageFacade, 
		DomainInfoProvider $domainInfoProvider
	) {
		$this->articleFacade = $articleFacade;
		$this->pageFacade = $pageFacade;
		$this->domainInfoProvider = $domainInfoProvider;
	}
	
	public function createRoutes(string $locale): RouteList
	{
		$router = new RouteList('Front');
		$domainInfo = $this->domainInfoProvider->provide();
		
		foreach ($this->articleFacade->getAll() as $article) {
			$router->addRoute($domainInfo->getPrimaryDomain() . '/blog/' . ($locale === 'cs' ? $article->getRouteCzech() : $article->getRouteEnglish()), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId(),
				'locale' => $locale
			]);
			$router->addRoute($domainInfo->getSecondaryDomain() . '/blog/' . ($locale === 'cs' ? $article->getRouteEnglish() : $article->getRouteCzech()), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId(),
				'locale' => $locale === 'cs' ? 'en' : 'cs'
			]);
		}

		foreach ($this->pageFacade->getAll() as $page) {
			$router->addRoute($domainInfo->getPrimaryDomain() . '/' . ($locale === 'cs' ? $page->getRouteCzech() : $page->getRouteEnglish()), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId(),
				'locale' => $locale
			]);
			$router->addRoute($domainInfo->getSecondaryDomain() . '/' . ($locale === 'cs' ? $page->getRouteEnglish() : $page->getRouteCzech()), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId(),
				'locale' => $locale === 'cs' ? 'en' : 'cs'
			]);
		}
		
		apcu_store(sprintf('minecord_%s_router_' . $domainInfo->getPrimaryDomain(), $locale), $router);
		
		return $router;
	}
	
	public function getDynamicRouteList(string $locale): RouteList
	{
		$routeList = apcu_fetch(sprintf('minecord_%s_router_' . $this->domainInfoProvider->provide()->getPrimaryDomain(), $locale));
		
		if (!$routeList) {
			$routeList = $this->createRoutes($locale);
		}
		
		return $routeList;
	}
}
