<?php

declare(strict_types=1);

namespace Minecord\Model\Route;

use Minecord\Model\Article\ArticleFacade;
use Minecord\Model\Page\PageFacade;
use Nette\Application\Routers\RouteList;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

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
	
	public function createRoutes(string $locale): RouteList
	{
		$router = new RouteList('Front');
		
		$firstDomain = '//' . $_SERVER['SERVER_NAME'];
		if (Strings::contains($firstDomain, '.net')) {
			$secondDomain = str_replace('.net', '.cz', $firstDomain);
		} else {
			$secondDomain = str_replace('.cz', '.net', $firstDomain);
		}
		
		foreach ($this->articleFacade->getAll() as $article) {
			$router->addRoute($firstDomain . '/blog/' . ($locale === 'cs' ? $article->getRouteCzech() : $article->getRouteEnglish()), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId(),
				'locale' => $locale
			]);
			$router->addRoute($secondDomain . '/blog/' . ($locale === 'cs' ? $article->getRouteEnglish() : $article->getRouteCzech()), [
				Presenter::PRESENTER_KEY => 'Article',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $article->getId(),
				'locale' => $locale === 'cs' ? 'en' : 'cs'
			]);
		}

		foreach ($this->pageFacade->getAll() as $page) {
			$router->addRoute($firstDomain . '/' . ($locale === 'cs' ? $page->getRouteCzech() : $page->getRouteEnglish()), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId(),
				'locale' => $locale
			]);
			$router->addRoute($secondDomain . '/' . ($locale === 'cs' ? $page->getRouteEnglish() : $page->getRouteCzech()), [
				Presenter::PRESENTER_KEY => 'Page',
				Presenter::ACTION_KEY => 'default',
				'id' => (string) $page->getId(),
				'locale' => $locale === 'cs' ? 'en' : 'cs'
			]);
		}
		
		apcu_store(sprintf('minecord_%s_router', $locale), $router);
		
		return $router;
	}
	
	public function getDynamicRouteList(string $locale): RouteList
	{
		$routeList = apcu_fetch(sprintf('minecord_%s_router', $locale));
		
		if (!$routeList) {
			$routeList = $this->createRoutes($locale);
		}
		
		return $routeList;
	}
}
