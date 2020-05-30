<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

use Minecord\Model\Sms\Record\Event\SmsRecordPreCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			SmsRecordPreCreatedEvent::class => 'onSmsRecordPreCreatedEvent'
		];
	}

	public function onSmsRecordPreCreatedEvent(SmsRecordPreCreatedEvent $event): void
	{
		$event->getSmsRecord()->setupAnswer('Dekujeme za zaslani SMS. Minecord Team.');
	}
}
