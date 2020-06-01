<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip;

use Minecord\Model\Payment\PayPal\PayPalCredentials;
use Minecord\Model\Payment\PayPal\PayPalPayment;
use Minecord\Model\Payment\PayPal\PayPalPaymentDataFactory;
use Minecord\Model\Payment\PayPal\PayPalPaymentFacade;
use Minecord\Model\Player\PlayerFacade;
use Minecord\Model\Product\Product;
use Minecord\Model\Product\ProductFacade;
use Minecord\Module\Front\BaseFrontPresenter;
use Minecord\Module\Front\Vip\Form\VipProductFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Ramsey\Uuid\Nonstandard\Uuid;
use Rixafy\Currency\CurrencyFacade;

/**
 * @property VipTemplate $template
 */
class VipPresenter extends BaseFrontPresenter
{
	/** @var Product[] */
	private array $ranks;
	
	private ProductFacade $productFacade;
	private PlayerFacade $playerFacade;
	private VipProductFormFactory $vipProductFormFactory;
	private PayPalPaymentDataFactory $payPalPaymentDataFactory;
	private PayPalPaymentFacade $payPalPaymentFacade;
	private PayPalCredentials $payPalCredentials;
	private CurrencyFacade $currencyFacade;

	public function __construct(
		ProductFacade $productFacade,
		PlayerFacade $playerFacade,
		VipProductFormFactory $vipProductFormFactory,
		PayPalPaymentDataFactory $payPalPaymentDataFactory,
		PayPalPaymentFacade $payPalPaymentFacade,
		PayPalCredentials $payPalCredentials, 
		CurrencyFacade $currencyFacade
	) {
		parent::__construct();
		$this->productFacade = $productFacade;
		$this->playerFacade = $playerFacade;
		$this->vipProductFormFactory = $vipProductFormFactory;
		$this->payPalPaymentDataFactory = $payPalPaymentDataFactory;
		$this->payPalPaymentFacade = $payPalPaymentFacade;
		$this->payPalCredentials = $payPalCredentials;
		$this->currencyFacade = $currencyFacade;
	}

	public function actionDefault(): void
	{
		$this->ranks = $this->productFacade->getAllRanks();
	}
	
	public function renderDefault(): void
	{
		$this->template->ranks = $this->ranks;
		$this->template->latestVipActivations = $this->playerFacade->getLatestVipActivations(5);
		$this->template->payPalClientId = $this->payPalCredentials->getClientId();
		$this->template->czkCurrency = $this->currencyFacade->getByCode('CZK');
	}
	
	public function handleSuccess(): void
	{
		$this->template->paymentSuccessful = true;
		$this->redrawControl('productSnippetContainer');
	}

	protected function createComponentProductForm(): Multiplier
	{
		return new Multiplier(function (string $id) {
			$form = $this->vipProductFormFactory->create(Uuid::fromString($id));
			
			$form->onSuccess[] = function (Form $form, array $data) use ($id) {
				$this->template->payPalPayment = $this->payPalPaymentFacade->create($this->payPalPaymentDataFactory->createFromFormData($data));
				$this->redrawControl('productSnippetContainer');
			};
			
			return $form;
		});
	}
}
