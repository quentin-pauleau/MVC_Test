<?php
namespace Core\Responses;

use Modules\Http\HttpHeader;
use Controllers\Actions\ActionsConversation;
use Controllers\Actions\ActionsMenu;
use Controllers\ControllerConversation;
use Controllers\ControllerMenu;

/**
 * Redirect the user to the previous route
 */
class RewindRedirectionResponse extends RedirectionResponse
{

	public function __construct(int $statusCode = 302, ?HttpHeader $headers = null) {
		// get the length of the start of the url (ex : http://localhost/ezged/xpert/index.php?)
		$length = strlen(explode('?', $_SERVER['HTTP_REFERER'])[0]) + 1;
		$uri = substr_replace($_SERVER['HTTP_REFERER'], '', 0, $length);
		parent::__construct($uri, $statusCode, $headers);
	}
}