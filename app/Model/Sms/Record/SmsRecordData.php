<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use DateTime;

final class SmsRecordData
{
	public string $text;
	public string $provider;
	public string $shortcode;
	public string $phone;
	public string $externalId;
	public bool $requireConfirmation = false;
	public ?DateTime $confirmedAt = null;
}
