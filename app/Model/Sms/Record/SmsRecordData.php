<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use DateTime;

final class SmsRecordData
{
	public string $text;
	public string $operator;
	public string $shortcode;
	public string $phone;
	public string $externalId;
	public string $country;
	public ?string $answer = null;
	public bool $requireConfirmation = false;
	public int $attempt;
	public DateTime $sentAt;
}
