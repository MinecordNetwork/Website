<?php

declare(strict_types=1);

namespace App\Model\Sms\Record\Event;

use App\Model\Sms\Record\SmsRecord;

class SmsRecordConfirmedEvent
{
    public function __construct(
        private SmsRecord $smsRecord
    ) {}

    public function getSmsRecord(): SmsRecord
    {
        return $this->smsRecord;
    }
}
