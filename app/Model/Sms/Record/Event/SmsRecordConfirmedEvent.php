<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record\Event;

use Minecord\Model\Sms\Record\SmsRecord;

class SmsRecordConfirmedEvent
{
	private SmsRecord $smsRecord;

	public function __construct(SmsRecord $smsRecord)
	{
		$this->smsRecord = $smsRecord;
	}

	public function getSmsRecord(): SmsRecord
	{
		return $this->smsRecord;
	}
}
