<?php

declare(strict_types=1);

namespace App\Module\Front\Vip;

use App\Model\Payment\PayPal\PayPalCredentials;
use App\Model\Payment\PayPal\PayPalPaymentDataFactory;
use App\Model\Payment\PayPal\PayPalPaymentFacade;
use App\Model\Player\PlayerFacade;
use App\Model\Product\Product;
use App\Model\Product\ProductFacade;
use App\Module\Front\BaseFrontPresenter;
use App\Module\Front\Vip\Form\VipProductFormFactory;
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

    public function __construct(
        private ProductFacade $productFacade,
        private PlayerFacade $playerFacade,
        private VipProductFormFactory $vipProductFormFactory,
        private PayPalPaymentDataFactory $payPalPaymentDataFactory,
        private PayPalPaymentFacade $payPalPaymentFacade,
        private PayPalCredentials $payPalCredentials,
        private CurrencyFacade $currencyFacade
    ) {
        parent::__construct();
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
            
            $form->onSuccess[] = function (Form $form, array $data) {
                $this->template->payPalPayment = $this->payPalPaymentFacade->create($this->payPalPaymentDataFactory->createFromFormData($data));
                $this->redrawControl('productSnippetContainer');
            };
            
            return $form;
        });
    }
}
