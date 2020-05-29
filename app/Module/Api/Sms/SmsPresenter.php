<?php

declare(strict_types=1);

namespace Minecord\Module\Api\Sms;

use DateTime;
use Minecord\Model\Sms\Record\SmsRecordData;
use Minecord\Model\Sms\Record\SmsRecordFacade;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

class SmsPresenter extends Presenter
{
	private SmsRecordFacade $smsRecordFacade;

	public function __construct(SmsRecordFacade $smsRecordFacade)
	{
		parent::__construct();
		$this->smsRecordFacade = $smsRecordFacade;
	}

	public function actionTest(string $id, string $sms, string $shortcode, string $phone, string $operator, string $timestamp, string $country, int $att): void
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
		
		$this->smsRecordFacade->create($data);
		
		$this->sendResponse(new TextResponse('Dekujeme za zaslani SMS.'));
	}
	
	public function beforeRender()
	{

	}
}
