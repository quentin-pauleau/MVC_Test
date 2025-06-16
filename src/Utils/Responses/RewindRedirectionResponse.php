<?php
namespace Utils\Responses;

use Controllers\Actions\ActionsConversation;
use Controllers\Actions\ActionsMenu;
use Controllers\ControllerConversation;
use Controllers\ControllerMenu;
/**
 * Redirect the user to the previous route
 */
class RewindRedirectionResponse extends RedirectionResponse
{
	private const REWIND_DISABLED_ROUTE = [
		"controller=".ControllerMenu::class."&action=".ActionsMenu::LOGIN => "controller=".ControllerMenu::class."&action=".ActionsMenu::MAIN,
		"controller=".ControllerConversation::class."&action=".ActionsConversation::THREAD => "controller=".ControllerMenu::class."&action=".ActionsMenu::MAIN,
	];

	public function __construct() {
		// get the length of the start of the url (ex : http://localhost/ezged/xpert/index.php?)
		$length = strlen(explode('?', $_SERVER['HTTP_REFERER'])[0]) + 1;
		
		$uri = substr_replace($_SERVER['HTTP_REFERER'], '', 0, $length);

		$this->uri = "index.php?".self::REWIND_DISABLED_ROUTE[$uri] ?? $_SERVER['HTTP_REFERER'];
	}
}