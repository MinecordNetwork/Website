<?php

declare(strict_types=1);

namespace Minecord\Router;

use Minecord\Model\Route\RouteProvider;
use Nette\Application\Routers\RouteList;
use Nette\Utils\Strings;

final class RouterFactory
{
	private RouteProvider $routeProvider;

	public function __construct(RouteProvider $routeProvider)
	{
		$this->routeProvider = $routeProvider;
	}

	public function create(): RouteList
	{
		$router = new RouteList;

		$router->add($this->createApiRouter());
		$router->add($this->createAdminRouter());
		$router->add($this->createFrontRouter());

		return $router;
	}

	private function createFrontRouter(): RouteList
	{
		$locale = isset($_SERVER['SERVER_NAME']) && substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs';
		
		$frontRouter = $this->routeProvider->getDynamicRouteList($locale);

		$firstDomain = '//' . $_SERVER['SERVER_NAME'];
		if (Strings::contains($firstDomain, '.net')) {
			$secondDomain = str_replace('.net', '.cz', $firstDomain);
		} else {
			$secondDomain = str_replace('.cz', '.net', $firstDomain);
		}
		
		$frontRouter->addRoute($firstDomain . '/<presenter=Homepage>[/<action=default>]', [
			'locale' => $locale
		]);

		$frontRouter->addRoute($secondDomain . '/<presenter=Homepage>[/<action=default>]', [
			'locale' => $locale === 'cs' ? 'en' : 'cs'
		]);

		return $frontRouter;
	}

	private function createAdminRouter(): RouteList
	{
		$adminRouter = new RouteList('Admin');

		$adminRouter->addRoute('admin[/<presenter=Auth>][/<action=default>][/<id>][/<name>]');

		return $adminRouter;
	}

	private function createApiRouter(): RouteList
	{
		$adminRouter = new RouteList('Api');

		$adminRouter->addRoute('api[/<presenter=Auth>][/<action=default>]');

		return $adminRouter;
	}
}
