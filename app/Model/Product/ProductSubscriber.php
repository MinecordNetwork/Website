<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

use Minecord\Model\Payment\PayPal\Event\PayPalPaymentAcceptedEvent;
use Minecord\Model\Product\Exception\ProductNotFoundException;
use Minecord\Model\Sms\Record\Event\SmsRecordConfirmedEvent;
use Minecord\Model\Sms\Record\Event\SmsRecordCreatedEvent;
use Minecord\Model\Sms\Record\Event\SmsRecordPreCreatedEvent;
use Minecord\Model\Sms\Record\SmsRecord;
use Minecord\Model\Sms\SmsCountryResolver;
use Minecord\Model\Sms\SmsPriceResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
	private ProductFacade $productFacade;

	public function __construct(ProductFacade $productFacade)
	{
		$this->productFacade = $productFacade;
	}

	public static function getSubscribedEvents()
	{
		return [
			SmsRecordPreCreatedEvent::class => 'onSmsRecordPreCreated',
			SmsRecordCreatedEvent::class => 'onSmsRecordCreated',
			SmsRecordConfirmedEvent::class => 'onSmsRecordConfirmed',
			PayPalPaymentAcceptedEvent::class => 'onPayPalPaymentAccepted'
		];
	}

	public function onPayPalPaymentAccepted(PayPalPaymentAcceptedEvent $event): void
	{
		$this->productFacade->onPurchase($event->getPayPalPayment()->getProduct()->getId(), $event->getPayPalPayment()->getNickname());
	}

	public function onSmsRecordPreCreated(SmsRecordPreCreatedEvent $event): void
	{
		$event->getSmsRecord()->setupAnswer($this->handleSms($event->getSmsRecord(), true));
	}

	public function onSmsRecordCreated(SmsRecordCreatedEvent $event): void
	{
		$smsRecord = $event->getSmsRecord();
		
		if (!$smsRecord->requireConfirmation()) {
			$this->handleSms($smsRecord);
		}
	}

	public function onSmsRecordConfirmed(SmsRecordConfirmedEvent $event): void
	{
		$this->handleSms($event->getSmsRecord());
	}
	
	private function handleSms(SmsRecord $smsRecord, bool $onlyAnswer = false): string
	{
		$smsTextParts = explode(' ', $smsRecord->getText());
		$country = SmsCountryResolver::resolve($smsRecord->getShortcode());
		$price = SmsPriceResolver::resolve($smsRecord->getShortcode());

		$invalidSms = function () use($smsRecord, $country): string {
			if ($country === 'CZ') {
				return 'Spatny format SMS, zaslana SMS nebyla zpoplatnena. Minecord.cz;FREE' . $smsRecord->getShortcode();
			} else {
				return 'Nespravny format SMS, zaslana SMS nebola spoplatnena. Minecord.cz;FREE8877';
			}
		};

		if (count($smsTextParts) < 3) {
			return $invalidSms();
		}
		
		try {
			if ($smsRecord->getCountry() === 'CZ') {
				$product = $this->productFacade->getBySmsCodeAndPriceCzech($smsTextParts[1], $price);
			} else {
				$product = $this->productFacade->getBySmsCodeAndPriceSlovak($smsTextParts[1], $price);
			}
			
			if (!$onlyAnswer) {
				$this->productFacade->onPurchase($product->getId(), $smsTextParts[2]);
			}
			
		} catch (ProductNotFoundException $e) {
			return $invalidSms();
		}
		
		if ($country === 'CZ') {
			return 'Dekujeme ze podporujete server Minecord.cz, balicek byl aktivovan!' . (($price > 100) ? ';' . $smsRecord->getShortcode() : '');
		} else {
			return 'Dakujeme ze podporujete server Minecord.cz, balicek bol aktivovany!;' . $smsRecord->getShortcode();
		}
	}
}
