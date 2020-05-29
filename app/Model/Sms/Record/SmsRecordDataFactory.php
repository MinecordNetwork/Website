<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

final class SmsRecordDataFactory
{
	public function createFromFormData(array $formData): SmsRecordData
	{
		$data = new SmsRecordData();
		$data->text = $formData['text'];
		$data->provider = $formData['provider'];
		$data->shortcode = $formData['shortcode'];
		$data->phone = $formData['phone'];
		$data->externalId = $formData['externalId'];
		$data->requireConfirmation = $formData['requireConfirmation'];
		$data->confirmedAt = $formData['confirmedAt'];
		$data->createdAt = $formData['createdAt'];

		return $data;
	}
}
