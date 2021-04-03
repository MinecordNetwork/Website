<?php

declare(strict_types=1);

namespace App\Model\Sms\Record;

use Ramsey\Uuid\Uuid;

final class SmsRecordFactory
{
    public function create(SmsRecordData $data): SmsRecord
    {
        return new SmsRecord(Uuid::uuid4(), $data);
    }
}
