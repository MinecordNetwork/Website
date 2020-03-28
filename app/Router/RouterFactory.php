<?php

declare(strict_types=1);

namespace Minecord\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
	public function create(): RouteList
	{
		$router = new RouteList;

		$router->add($this->createAdminRouter());
		$router->add($this->createFrontRouter());

		return $router;
	}

	private function createFrontRouter(): RouteList
	{
		$frontRouter = new RouteList('Front');

		$frontRouter->addRoute('<presenter=Homepage>/<action=default>');

		return $frontRouter;
	}

	private function createAdminRouter(): RouteList
	{
		$adminRouter = new RouteList('Admin');

		$adminRouter->addRoute('admin[/<domainHost>][/<presenter=Auth>][/<action=default>][/<id>][/<name>]');

		return $adminRouter;
	}
}
