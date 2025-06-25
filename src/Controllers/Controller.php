<?php
namespace Controllers;

use Core\Responses\Response;

abstract class Controller
{
	abstract public static function GetInstance();
	protected function __construct() { }

	/**
	 * Controller's router
	 * @param string $route
	 * @return Response
	 */
	abstract public function Route($action): Response;
}
