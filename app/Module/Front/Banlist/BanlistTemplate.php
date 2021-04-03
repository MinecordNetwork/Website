<?php

declare(strict_types=1);

namespace App\Module\Front\Banlist;

use App\Model\Banlist\Ban;
use App\Model\Payment\PayPal\PayPalPayment;
use App\Model\Product\Product;
use App\Module\Front\BaseFrontTemplate;
use Rixafy\Currency\Currency;

class BanlistTemplate extends BaseFrontTemplate
{
    /** @var Ban[] */
    public array $bans;
    public int $page;
    public int $pageCount;
    public ?Product $unBan = null;
    public string $payPalClientId;
    public Currency $czkCurrency;
    public ?PayPalPayment $payPalPayment = null;
    public bool $paymentSuccessful = false;
}
