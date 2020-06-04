<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Banlist;

use Minecord\Model\Banlist\BanlistFacade;
use Minecord\Model\Payment\PayPal\PayPalCredentials;
use Minecord\Model\Payment\PayPal\PayPalPaymentDataFactory;
use Minecord\Model\Payment\PayPal\PayPalPaymentFacade;
use Minecord\Model\Product\Exception\ProductNotFoundException;
use Minecord\Model\Product\ProductFacade;
use Minecord\Module\Front\BaseFrontPresenter;
use Minecord\Module\Front\Vip\Form\VipProductFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Ramsey\Uuid\Nonstandard\Uuid;
use Rixafy\Currency\CurrencyFacade;

/**
 * @property-read BanlistTemplate $template
 */
class BanlistPresenter extends BaseFrontPresenter
{
	private BanlistFacade $banlistFacade;
	private ProductFacade $productFacade;
	private CurrencyFacade $currencyFacade;
	private PayPalCredentials $payPalCredentials;
	private VipProductFormFactory $vipProductFormFactory;
	private PayPalPaymentFacade $payPalPaymentFacade;
	private PayPalPaymentDataFactory $payPalPaymentDataFactory;

	public function __construct(
		BanlistFacade $banlistFacade,
		ProductFacade $productFacade,
		CurrencyFacade $currencyFacade,
		PayPalCredentials $payPalCredentials,
		VipProductFormFactory $vipProductFormFactory,
		PayPalPaymentFacade $payPalPaymentFacade, 
		PayPalPaymentDataFactory $payPalPaymentDataFactory
	) {
		parent::__construct();
		$this->banlistFacade = $banlistFacade;
		$this->productFacade = $productFacade;
		$this->currencyFacade = $currencyFacade;
		$this->payPalCredentials = $payPalCredentials;
		$this->vipProductFormFactory = $vipProductFormFactory;
		$this->payPalPaymentFacade = $payPalPaymentFacade;
		$this->payPalPaymentDataFactory = $payPalPaymentDataFactory;
	}
	
	public function actionDefault(int $page = 1): void
	{
		$itemsPerPage = 15;
		$count = $this->banlistFacade->getCount();
		
		$pageCount = (int) (($count / $itemsPerPage) + ($count % $itemsPerPage === 0 ? 0 : 1));

		if ($page > $pageCount) {
			$this->redirect('default');
		}

		$this->template->page = $page;
		$this->template->pageCount = $pageCount;
		$this->template->bans = $this->banlistFacade->getAll($itemsPerPage, ($page - 1) * $itemsPerPage);

		try {
			$this->template->unBan = $this->productFacade->getBySmsCodeAndPriceSlovak('UNBAN', 6);
		} catch (ProductNotFoundException $e) {
			$this->template->unBan = null;
		}
	}

	public function renderDefault(): void
	{
		$this->template->payPalClientId = $this->payPalCredentials->getClientId();
		$this->template->czkCurrency = $this->currencyFacade->getByCode('CZK');
	}

	public function handleSuccess(): void
	{
		$this->template->paymentSuccessful = true;
		$this->redrawControl('productSnippet');
	}

	protected function createComponentProductForm(): Form
	{
		$form = $this->vipProductFormFactory->create($this->template->unBan->getId());

		$form->onSuccess[] = function (Form $form, array $data) {
			$this->template->payPalPayment = $this->payPalPaymentFacade->create($this->payPalPaymentDataFactory->createFromFormData($data));
			$this->redrawControl('productSnippet');
		};

		return $form;
	}
}
