<?php
namespace Modules\Routing;

use Modules\Routing\RoutingExceptionCodes as Codes;

class RoutingException extends \Exception
{
	public function __construct(Codes $code) {
		$message = match ($code)
		{
			// 3XX
			Codes::URL_PARAMETER_INVALID_LABEL => "Url Parameter invalid label",
			Codes::URL_PARAMETER_HAS_NO_MATCHING_LABEL => "Url Parameter invalid",
			Codes::REQUIRED_PARAMETER_MISSING => "Required parameter is missing from request",

			Codes::PARAMETER_INVALID => "Parameter is invalid",


			Codes::RESPONSE_TYPE_INVALID => "Response Type is not valid",
		};

		parent::__construct($message);
	}
}