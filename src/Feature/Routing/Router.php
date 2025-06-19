<?php
namespace Feature\Routing;

use Utils\Requests\Request;


/**
 * The core router
 */
final class Router {
	public function GetRequestedRoute() {
		$Request = Request::GetRequest();

		$controller = $Request->Query->Get('controller');
		
		$action = $Request->Query->Get('action');

		return;
	}


	public static function GetAllPossibleRoutes() {

	}
}