<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

use Minecord\Model\Product\Event\ProductPurchasedEvent;
use Minecord\Model\Product\Product;
use Minecord\Model\Server\ServerFacade;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
		$this->activate($event->getProduct(), $event->getNickname());
	}
	
	private function activate(Product $product, string $nickname): void
	{
		foreach ($this->serverFacade->getAll() as $server) {
			if ($server->rconPort !== null) {
				if ($product->isIsRank()) {
					$this->rconFacade->sendCommands([sprintf('cordex store %s SMS VIP', $nickname)], $server->host, $server->rconPort);
					$this->rconFacade->sendCommands([sprintf('cordex premium %s %s fake', $nickname, $product->getDuration())], $server->host, $server->rconPort);
					if ($server->name === 'lobby') {
						$this->rconFacade->sendCommands([sprintf('cordex premium %s %s', $nickname, $product->getDuration())], $server->host, $server->rconPort);
					}
				}
			}
		}
	}
}
