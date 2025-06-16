<?php
namespace Utils\Responses;

use Utils\Core;
use Utils\Responses\RedirectionResponse;

class RouteRedirectionResponse extends RedirectionResponse
{
	/**
	 * Create a {@see RedirectionResponse} from a given controller and action
	 * @param string $controller the controller to redirect to
	 * @param mixed $action the action of the controller
	 * @throws \Exception
	 * @return RedirectionResponse
	 */
	public function __construct(string $controller, ?string $action = null) {
		if (!Core::IsValidController($controller))
			throw new \Exception("Ce controller n'existe pas ou n'est pas un controller");

		$this->uri = "index.php?controller=$controller&action=$action";
	}


	/**
	 * Return a uri version of the route based of a given controller and action
	 * 
	 * @param string $controller the controller
	 * @param ?string $action the controller's action
	 * @return string the route uri (index.php?controller=$controller&action=$action)
	 */
	public static function PrepareURI(string $controller, ?string $action = null): string {
		if ($action === null)
			return "index.php?controller=$controller";

		return "index.php?controller=$controller&action=$action";
	}


}