<?php
namespace Utils;

use Exception;

use Utils\Responses\Response;
use Utils\Session\UserHelper;
use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;

use Controllers\Controller;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Controllers\ControllerFormCDEClient;

final class Core {
	public static string $controller;
	public static $action;
	public const DEFAULT_CONTROLLER = ControllerMenu::class;


	/**
	 * Initialise the main components of the app
	 * @return void
	 */
	public static function init(): void {
		self::SetOriginConfig();
		UserHelper::init();
		DataHelper::init();
		ErrorHelper::init();
		self::loadRoute();
	}

	private static function loadRoute(): void {
		if (!isset($_SESSION)) {
			session_start();
		}
		ControllerFormCDEClient::class;

		self::$controller = isset($_GET["controller"]) && Core::isValidController($_GET["controller"]) 
				? $_GET["controller"] 
				: Core::DEFAULT_CONTROLLER;

		self::$action = $_GET["action"] ?? null;

		//* Handle the route in different ways depending of the session state
		if (UserHelper::IsLoggedIn()) {
			if (UserHelper::IsSessionTimedOut()) {
				self::HandleSessionTimeOut();
				return;
			}

			$_SESSION["user"]['last_activity'] = time();
			return;
		}

		
		if (!UserHelper::isLoggingIn()) {
			self::HandleNoSession();
			return;
		}
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
	 * Redirect the user to the logging page and save the route, the user is redirected to that route once connected
	 * @return void
	 */
	private static function HandleNoSession(): void {
		DataHelper::Set('saved_controller', self::$controller);
		DataHelper::Set('saved_action', self::$action);
		ErrorHelper::set("disconected", "Veuillez vous connectez, pour acceder au contenu");
		Core::Redirect(ActionsMenu::LOGIN, ControllerMenu::class);
		return;
	}


	/**
	 * Handle the session time out
	 * @return never
	 */
	private static function HandleSessionTimeOut(): void {
		UserHelper::LogOut();

		DataHelper::Set('saved_controller', self::$controller);
		DataHelper::Set('saved_action', self::$action);

		ErrorHelper::set("disconected", "Vous avez été déconnecter pour inactivitée.<br>Veuillez vous reconnecter, pour acceder au contenu");
		Core::Redirect(ActionsMenu::LOGIN, ControllerMenu::class);
		return;
	}


	/**
	 * Start the controller and handle the responce
	 * @return void
	 */
	public static function StartController (): void {
		if (!self::IsValidController(self::$controller))
			throw new Exception("The controller is invalid");

		$Controller = Core::$controller::GetInstance();

		if (!($Controller instanceof Controller))
			throw new Exception("The controller is invalid");

		$Response = $Controller->Route(Core::$action);

		if (!($Response instanceof Response))
			throw new Exception("The response returned by the application is invalid");

		$Response->Process();
		exit;
	}


	public static function IsValidController(string &$controller): bool {
		if (str_starts_with2($controller, "ControllersController"))
			$controller = "Controllers\\".explode("Controllers", $controller)[1];

		return class_exists($controller) && is_subclass_of($controller, Controller::class);
	}


	/**
	 * Redirect to an action of a controller
	 * @param mixed $action
	 * @param string $controller
	 * @throws \Exception
	 * @return never
	 */
	public static function Redirect($action, string $controller): void {
		if (!self::IsValidController($controller))
			throw new Exception("Ce controller n'existe pas ou n'est pas un controller");
	
		header("Location: index.php?controller=$controller&action=$action");
		exit;
	}

	public static function NavigationRewind(): void {
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
	}
}