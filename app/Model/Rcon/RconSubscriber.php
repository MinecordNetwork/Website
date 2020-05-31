<?php

declare(strict_types=1);

namespace Minecord\Model\Rcon;

use Minecord\Model\Product\Event\ProductPurchasedEvent;
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
		foreach ($this->serverFacade->getAll() as $server) {
			if ($server->rconPort !== null) {
				if ($event->getProduct()->isIsRank()) {
					$this->rconFacade->sendCommands([sprintf('cordex store %s SMS VIP', $event->getNickname())], $server->host, $server->rconPort);
					$this->rconFacade->sendCommands([sprintf('cordex premium %s %s fake', $event->getNickname(), $event->getProduct()->getDuration())], $server->host, $server->rconPort);
					if ($server->name === 'lobby') {
						$this->rconFacade->sendCommands([sprintf('cordex premium %s %s', $event->getNickname(), $event->getProduct()->getDuration())], $server->host, $server->rconPort);
					}
				}
			}
		}
	}
}
