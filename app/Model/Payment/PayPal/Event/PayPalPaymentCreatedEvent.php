<?php

declare(strict_types=1);

namespace Minecord\Model\Payment\PayPal\Event;

use Minecord\Model\Payment\PayPal\PayPalPayment;

class PayPalPaymentCreatedEvent
{
	private PayPalPayment $payPalPayment;

	public function __construct(PayPalPayment $payPalPayment)
	{
		$this->payPalPayment = $payPalPayment;
	}

	public function getPayPalPayment(): PayPalPayment
	{
		return $this->payPalPayment;
	}
}
