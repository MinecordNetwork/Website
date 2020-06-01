<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Payment\PayPal\PayPalPayment;
use Minecord\Model\Player\Vip\PlayerVipActivation;
use Minecord\Model\Product\Product;
use Minecord\Module\Front\BaseFrontTemplate;
use Rixafy\Currency\Currency;

class VipTemplate extends BaseFrontTemplate
{
	/** @var Product[] */
	public array $ranks;
	
	/** @var PlayerVipActivation[] */
	public array $latestVipActivations;
	
	public ?PayPalPayment $payPalPayment = null;
	public string $payPalClientId;
	public Currency $czkCurrency;
	public bool $paymentSuccessful = false;
}
