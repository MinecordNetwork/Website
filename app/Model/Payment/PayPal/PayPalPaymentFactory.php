<?php

declare(strict_types=1);

namespace Minecord\Model\Payment\PayPal;

use Ramsey\Uuid\Uuid;

class PayPalPaymentFactory
{
	public function create(PayPalPaymentData $data): PayPalPayment
	{
		return new PayPalPayment(Uuid::uuid4(), $data);
	}
}
