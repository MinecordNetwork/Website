<?php

declare(strict_types=1);

namespace Minecord\Router;

use Minecord\Model\Domain\Info\DomainInfoProvider;
use Minecord\Model\Route\RouteProvider;
use Nette\Application\Routers\RouteList;
use Nette\Utils\Strings;

final class RouterFactory
{
	private RouteProvider $routeProvider;
	private DomainInfoProvider $domainInfoProvider;

	public function __construct(
		RouteProvider $routeProvider, 
		DomainInfoProvider $domainInfoProvider
	) {
		$this->routeProvider = $routeProvider;
		$this->domainInfoProvider = $domainInfoProvider;
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

		$domainInfo = $this->domainInfoProvider->provide();
		
		$frontRouter->addRoute($domainInfo->getPrimaryDomain() . '/<presenter=Homepage>[/<action=default>]', [
			'locale' => $locale
		]);

		$frontRouter->addRoute($domainInfo->getSecondaryDomain() . '/<presenter=Homepage>[/<action=default>]', [
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
