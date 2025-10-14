<?php
namespace Modules\Eclipse;

use Exception;

final class Core {


	/**
	 * Initialise the main components of the app
	 * @return void
	 */
	public static function Init(): void {
	}


	public static function SetOriginConfig() {
		$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? null;

		if ($origin === null) {
			session_abort();
		} elseif (
			str_starts_with2($origin, 'http://localhost') 
			&& str_starts_with2($origin, 'https://xpert.ezgedenligne.fr')
		) {
			die("the origin is not an allowed domain");
		}

		header("Access-Control-Allow-Origin: $origin");

		// Specify domains from which requests are allowed
		header('Access-Control-Allow-Origin: *');

		// Specify which request methods are allowed
		header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE');

		// Set the age to 1 day to improve speed/caching.
		header('Access-Control-Max-Age: 86400');
	}

	/**
	 * Start the controller and handle the responce
	 * @return void
	 */
	public static function StartApp (): void {
		$fullUri = explode('?', $_SERVER['REQUEST_URI'])[0];

		$uri = explode('/', trim($fullUri, '/'));

		
	}
}