<?php

declare(strict_types=1);

namespace App\Model\Payment\PayPal\Event;

use App\Model\Payment\PayPal\PayPalPayment;

class PayPalPaymentCreatedEvent
{
    public function __construct(
        private PayPalPayment $payPalPayment
    ) {}

    public function getPayPalPayment(): PayPalPayment
    {
        return $this->payPalPayment;
    }
}
