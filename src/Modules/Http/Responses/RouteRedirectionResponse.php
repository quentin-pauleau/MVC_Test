<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

class ActionRedirectionResponse extends RedirectionResponse
{
	/**
	 * Create a RedirectionResponse from a given controller/action
	 */
	public function __construct(string $controller, ?string $action = null, int $statusCode = 302, ?HttpHeader $headers = null)
	{
		parent::__construct(self::PrepareURI($controller, $action), $statusCode, $headers);
	}

	/**
	 * Return a uri version of the route based on a given controller and action
	 * @return string the route uri (index.php?controller=$controller&action=$action)
	 */
	public static function PrepareURI(string $controller, ?string $action = null): string {
		if ($action === null)
			return "index.php?controller=$controller";
		
		return "index.php?controller=$controller&action=$action";
	}
}