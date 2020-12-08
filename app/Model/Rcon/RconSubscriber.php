<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

use Minecord\Model\Product\Event\ProductPurchasedEvent;
use Minecord\Model\Product\Product;
use Minecord\Model\Server\ServerFacade;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\Debugger;

class RconSubscriber implements EventSubscriberInterface
{
	private ServerFacade $serverFacade;
	private RconFacade $rconFacade;

	public function __construct(
		ServerFacade $serverFacade, 
		RconFacade $rconFacade
	) {
		$this->serverFacade = $serverFacade;
		$this->rconFacade = $rconFacade;
	}

	public static function getSubscribedEvents()
	{
		return [
			ProductPurchasedEvent::class => 'onProductPurchased'
		];
	}

	public function onProductPurchased(ProductPurchasedEvent $event): void
	{
		$this->activate($event->getProduct(), $event->getNickname(), $event->getMethod());
	}
	
	private function activate(Product $product, string $nickname, string $method): void
	{
		foreach ($this->serverFacade->getAll() as $server) {
			if ($server->rconPort !== null) {
				if ($product->isRank()) {
					if (Debugger::$productionMode) {
						$this->rconFacade->sendCommands([sprintf('cordex store %s %s VIP', $nickname, $method)], $server->host, $server->rconPort);
						$this->rconFacade->sendCommands([sprintf('cordex premium %s %s fake', $nickname, $product->getDuration())], $server->host, $server->rconPort);
						if ($server->name === 'lobby') {
							$this->rconFacade->sendCommands([sprintf('cordex premium %s %s', $nickname, $product->getDuration())], $server->host, $server->rconPort);
						}
						
					} else {
						$this->rconFacade->sendCommands([sprintf('msg %s Development mode', $nickname)], $server->host, $server->rconPort);
					}
				}
			}
		}
		
		/*if ($product->isUnban()) {
			$this->rconFacade->sendCommandsToProxy([sprintf('unban %s', $nickname)]);
		}*/
	}
}
