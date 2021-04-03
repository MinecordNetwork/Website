<?php

declare(strict_types=1);

namespace App\Module\Api\PayPal;

use App\Model\Payment\PayPal\PayPalCredentials;
use App\Model\Payment\PayPal\PayPalPaymentFacade;
use Nette\Application\UI\Presenter;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Ramsey\Uuid\Uuid;
use Tracy\Debugger;

final class PayPalPresenter extends Presenter
{
    public function __construct(
        private PayPalPaymentFacade $payPalPaymentFacade,
        private PayPalCredentials $payPalCredentials
    ) {
        parent::__construct();
    }

    public function actionDefault(string $orderId = null): void
    {
        if ($orderId === null) {
            return;
        }

        $request = new OrdersCaptureRequest($orderId);

        if (Debugger::$productionMode) {
            $environment = new ProductionEnvironment($this->payPalCredentials->getClientId(), $this->payPalCredentials->getClientSecret());
        } else {
            $environment = new SandboxEnvironment($this->payPalCredentials->getClientId(), $this->payPalCredentials->getClientSecret());
        }

        $client = new PayPalHttpClient($environment);

        $response = $client->execute($request);

        /** @var object $result */
        $result = $response->result;

        if ((string) $result->status === 'COMPLETED') {
            $paymentId = Uuid::fromString($result->purchase_units[0]->reference_id);
            $email = (string) $result->payer->email_address;
            $price = (float) $result->purchase_units[0]->payments->captures[0]->amount->value;
            $currency = (string) $result->purchase_units[0]->payments->captures[0]->amount->currency_code;
            $this->payPalPaymentFacade->onAccept($paymentId, $email, $price, $currency);
        }

        $this->sendJson([
            'success' => true
        ]);
    }
}
