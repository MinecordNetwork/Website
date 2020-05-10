<?php

declare(strict_types=1);

namespace Minecord\Router;

use Minecord\Model\Route\RouteProvider;
use Nette\Application\Routers\RouteList;

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

		$router->add($this->createAdminRouter());
		$router->add($this->createFrontRouter());

		return $router;
	}

	private function createFrontRouter(): RouteList
	{
		$locale = substr($_SERVER['SERVER_NAME'], -3) === 'net' ? 'en' : 'cs';
		
		$frontRouter = $this->routeProvider->getDynamicRouteList($locale);

		$frontRouter->addRoute('<presenter=Homepage>/<action=default>');

		return $frontRouter;
	}

	private function createAdminRouter(): RouteList
	{
		$adminRouter = new RouteList('Admin');

		$adminRouter->addRoute('admin[/<presenter=Auth>][/<action=default>][/<id>][/<name>]');

		return $adminRouter;
	}
}
