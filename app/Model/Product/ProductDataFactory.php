<?php

declare(strict_types=1);

namespace Minecord\Model\Product;

final class ProductDataFactory
{
	public function createFromFormData(array $formData): ProductData
	{
		$data = new ProductData();
		$data->nameEnglish = $formData['nameEnglish'];
		$data->nameCzech = $formData['nameCzech'];
		$data->descriptionEnglish = $formData['descriptionEnglish'];
		$data->descriptionCzech = $formData['descriptionCzech'];
		$data->smsCode = $formData['smsCode'];
		$data->price = $formData['price'];
		$data->priceCzechSms = $formData['priceCzechSms'];
		$data->priceSlovakSms = $formData['priceSlovakSms'];
		$data->isRank = $formData['isRank'];

		return $data;
	}
}
