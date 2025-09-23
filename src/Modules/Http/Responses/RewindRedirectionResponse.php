<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Redirect the user to the previous route
 */
class RewindRedirectionResponse extends RedirectionResponse
{

	public function __construct(int $statusCode = 302, ?HttpHeader $headers = null) {
		$length = strlen(explode('?', $_SERVER['HTTP_REFERER'])[0]) + 1;
		$uri = substr_replace($_SERVER['HTTP_REFERER'], '', 0, $length);
		parent::__construct($uri, $statusCode, $headers);
	}
}