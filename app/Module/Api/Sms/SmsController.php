<?php

declare(strict_types=1);

namespace Minecord\Module\Api\Sms;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use Nette\Utils\Json;

/**
 * @Path("/api/v1/sms")
 */
class SmsController implements IController
{
	/**
	 * @Path("/hello/{code}")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="code", type="int", description="Sms code")
	 * })
	 */
	public function getSmsPoints(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$code = (int) $request->getParameter('code');

		$response->writeBody(Json::encode([
			'points' => $code
		]));

		return $response;
	}
}
