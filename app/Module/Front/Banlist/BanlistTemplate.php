<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Banlist;

use Minecord\Model\Banlist\Ban;
use Minecord\Model\Payment\PayPal\PayPalPayment;
use Minecord\Model\Product\Product;
use Minecord\Module\Front\BaseFrontTemplate;
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
