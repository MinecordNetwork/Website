<?php

declare(strict_types=1);

namespace App\Model\Product;

use App\Model\Payment\PayPal\Event\PayPalPaymentAcceptedEvent;
use App\Model\Product\Exception\ProductNotFoundException;
use App\Model\Sms\Record\Event\SmsRecordConfirmedEvent;
use App\Model\Sms\Record\Event\SmsRecordCreatedEvent;
use App\Model\Sms\Record\Event\SmsRecordPreCreatedEvent;
use App\Model\Sms\Record\SmsRecord;
use App\Model\Sms\SmsCountryResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ProductFacade $productFacade
    ) {}

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
        $this->productFacade->onPurchase($event->getPayPalPayment()->getProduct()->getId(), $event->getPayPalPayment()->getNickname(), 'PayPal');
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
        $country = SmsCountryResolver::resolve($smsRecord->getShortcode());

        $invalidSms = function (float $price) use ($smsRecord, $country): string {
            if ($country === 'CZ') {
                if ($price > 100) {
                    return 'Spatny format SMS, zaslana SMS nebyla zpoplatnena. Minecord.cz;FREE' . $smsRecord->getShortcode() . $price;
                } else {
                    return 'Spatny format SMS, kontaktujte nas na nasem discordu, naleznete ho na Minecord.cz';
                }
            } else {
                return 'Nespravny format SMS, zaslana SMS nebola spoplatnena. Minecord.cz;FREE8877';
            }
        };

        $smsTextParts = explode(' ', $smsRecord->getText());
        if (count($smsTextParts) < 4) {
            return $invalidSms(isset($smsTextParts[1]) ? (float) $smsTextParts[1] : 0);
        }
        
        $price = (float) $smsTextParts[1];
        $code = $smsTextParts[2];
        $nick = $smsTextParts[3];
        
        try {
            if ($country === 'CZ') {
                $product = $this->productFacade->getBySmsCodeAndPriceCzech($code, $price);
            } else {
                $product = $this->productFacade->getBySmsCodeAndPriceSlovak($code, $price);
            }
            
            if (!$onlyAnswer) {
                $this->productFacade->onPurchase($product->getId(), $nick, 'SMS');
            }
            
        } catch (ProductNotFoundException $e) {
            return $invalidSms($price);
        }
        
        if ($country === 'CZ') {
            return 'Dekujeme ze podporujete server Minecord.cz, balicek byl aktivovan!' . (($price > 100) ? ';' . $smsRecord->getShortcode() . $price : '');
        } else {
            return 'Dakujeme ze podporujete server Minecord.cz, balicek bol aktivovany!;' . $smsRecord->getShortcode() . str_pad(number_format($price, 2, '', ''), 4, '0', STR_PAD_LEFT);
        }
    }
}
