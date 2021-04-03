<?php

declare(strict_types=1);

namespace App\Model\Payment\PayPal;

use App\Model\Product\ProductFacade;
use Ramsey\Uuid\Nonstandard\Uuid;
use Rixafy\Currency\CurrencyFacade;

class PayPalPaymentDataFactory
{
    private ProductFacade $productFacade;
    private CurrencyFacade $currencyFacade;

    public function __construct(
        ProductFacade $productFacade, 
        CurrencyFacade $currencyFacade
    ) {
        $this->productFacade = $productFacade;
        $this->currencyFacade = $currencyFacade;
    }

    public function createFromFormData(array $formData): PayPalPaymentData
    {
        $data = new PayPalPaymentData();
        $data->nickname = $formData['nickname'];
        $data->product = $this->productFacade->get(Uuid::fromString($formData['product']));
        $data->price = $data->product->getPrice();
        $data->currency = $this->currencyFacade->getByCode('EUR');

        return $data;
    }
}
