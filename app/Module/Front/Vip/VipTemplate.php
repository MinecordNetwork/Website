<?php

declare(strict_types=1);

namespace App\Module\Front\Vip;

use App\Model\Payment\PayPal\PayPalPayment;
use App\Model\Player\Vip\PlayerVipActivation;
use App\Model\Product\Product;
use App\Module\Front\BaseFrontTemplate;
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
