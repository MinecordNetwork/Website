<?php

declare(strict_types=1);

namespace App\Module\Api\Sms;

use App\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use DateTime;
use App\Model\Discord\DiscordWebhook;
use App\Model\Sms\Record\SmsRecordData;
use App\Model\Sms\Record\SmsRecordFacade;
use Nette\Application\AbortException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

class SmsPresenter extends Presenter
{
    public function __construct(
        private SmsRecordFacade $smsRecordFacade,
        private DiscordWebhook $discordWebhook
    ) {
        parent::__construct();
    }

    /**
     * @throws SmsRecordNotFoundException
     * @throws AbortException
     */
    public function actionConfirm(int $id, int $request, string $status, string $timestamp, ?string $message = null): void
    {
        $smsRecord = $this->smsRecordFacade->getByExternalId($request);
        
        if ($status === 'DELIVERED') {
            $this->smsRecordFacade->onConfirm($smsRecord->getId());
            
        } else {
            $this->discordWebhook->sendMessage('SMS Log', sprintf('SMS with ID %s failed to confirm SMS with id %s, status: %s, reason: %s', $id, $request, $status, $message));
        }

        $this->getHttpResponse()->setCode(204);
        $this->terminate();
    }

    /**
     * @throws AbortException
     */
    public function actionAccept(string $id, string $sms, string $shortcode, string $phone, string $operator, string $timestamp, string $country, int $att): void
    {
        $this->getHttpResponse()->setCode(200);
        $this->getHttpResponse()->setContentType('text/plain');
        
        $data = new SmsRecordData();
        $data->externalId = $id;
        $data->text = $sms;
        $data->phone = $phone;
        $data->operator = $operator;
        $data->shortcode = $shortcode;
        $data->sentAt = DateTime::createFromFormat('Y-m-d\TH:i:s', substr($timestamp, 0, 19));
        $data->country = $country;
        $data->attempt = $att;
        $data->requireConfirmation = !str_starts_with($shortcode, '90333');
        
        $smsRecord = $this->smsRecordFacade->create($data);
        
        $this->sendResponse(new TextResponse($smsRecord->getAnswer()));
    }
}
