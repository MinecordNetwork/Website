<?php

declare(strict_types=1);

namespace App\Module\Front\Banlist;

use App\Model\Banlist\BanlistFacade;
use App\Model\Payment\PayPal\PayPalCredentials;
use App\Model\Payment\PayPal\PayPalPaymentDataFactory;
use App\Model\Payment\PayPal\PayPalPaymentFacade;
use App\Model\Product\Exception\ProductNotFoundException;
use App\Model\Product\ProductFacade;
use App\Module\Front\BaseFrontPresenter;
use App\Module\Front\Vip\Form\VipProductFormFactory;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\Exception\CurrencyNotFoundException;

/**
 * @property BanlistTemplate $template
 */
class BanlistPresenter extends BaseFrontPresenter
{
    public function __construct(
        private BanlistFacade $banlistFacade,
        private ProductFacade $productFacade,
        private CurrencyFacade $currencyFacade,
        private PayPalCredentials $payPalCredentials,
        private VipProductFormFactory $vipProductFormFactory,
        private PayPalPaymentFacade $payPalPaymentFacade,
        private PayPalPaymentDataFactory $payPalPaymentDataFactory
    ) {
        parent::__construct();
    }

    /**
     * @throws AbortException
     * @throws BadRequestException
     */
    public function actionDefault(int $page = 1): void
    {
        throw new BadRequestException();
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

    /**
     * @throws CurrencyNotFoundException
     */
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

        $form->onSuccess[] = function (array $data): void {
            $this->template->payPalPayment = $this->payPalPaymentFacade->create($this->payPalPaymentDataFactory->createFromFormData($data));
            $this->redrawControl('productSnippet');
        };

        return $form;
    }
}
