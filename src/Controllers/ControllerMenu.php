<?php
namespace Controllers;

use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\Controller;
use Controllers\Actions\ActionsMenu;

use Exception;
use Models\ModelUser;

use Traits\Singleton;
use Core\Database\DatabaseException;
use Core\Requests\Request;
use Core\Responses\HTMLResponse;
use Core\Responses\Response;
use Core\Responses\RouteRedirectionResponse;
use Core\Session\DataHelper;
use Core\Session\UserHelper;
use Core\Session\ErrorHelper;

final class ControllerMenu extends Controller
{
	use Singleton;

	public function Route($action): Response {
		switch ($action) {
			case ActionsMenu::LOGIN:
				return self::Login();

			case ActionsMenu::LOGIN_PROCESS:
				return self::LoginProcess();
			
			case ActionsMenu::LOGOUT_PROCESS:
				return self::LogOutProcess();

			case ActionsMenu::MAIN:
				return self::MainMenu();

			default:
				return self::MainMenu();
		}
	}


	/**
	 * Display the login page
	 * @return HTMLResponse
	 */
	public function Login(): Response {
		return new HTMLResponse (
			"public/Views/menu/login.php",
			[
				"errors" => ErrorHelper::GetAll()
			]
		);
	}


	/**
	 * Handling the the connection of the user to the app, from the form to the redirection to the page
	 * @return HTMLResponse|RouteRedirectionResponse
	 */
	public function LoginProcess(): Response {
		ErrorHelper::clear();

		$TempUser = new class {
			public string $login;
			public string $password;
		};

		$Request = Request::GetRequest();

		$result = true;

		switch ($login = $Request->Data->FilterString('login')) {
			case null:
				ErrorHelper::Set("nom", "Le nom est obligatoire");
				$result = false;
				break;
	
			case false:
				ErrorHelper::Set("nom", "Le nom est invalide");
				$result = false;
				break;
	
			default:
				$TempUser->login = $login;
				break;
		}

		switch ($pass = $Request->Data->FilterString('pass')) {
			case null:
				ErrorHelper::Set("pass", "Le mot de passe est obligatoire");
				$result = false;
				break;
	
			case false:
				ErrorHelper::Set("pass", "Le mot de passe est invalide");
				$result = false;
				break;
	
			default:
				$TempUser->password = $pass;
				break;
		}

		if (!$result)
			return new RouteRedirectionResponse(self::class, ActionsMenu::LOGIN);
		
		try
		{
			$User = ModelUser::GetInstance()->GetByLogin($TempUser->login);
			
			if ($User === null)
				throw new Exception('User not found');
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::SetDefault("username", "Identifiant ou mot de passe incorrect");
			ErrorHelper::SetDebug("username", $e);

			return new RouteRedirectionResponse(self::class, ActionsMenu::LOGIN);
		}

		$hash = hash("md5", hash("md5", $TempUser->password));
		unset($TempUser);

		if ($User->pass != $hash) {
			ErrorHelper::SetDefault("password", "Identifiant ou mot de passe incorrect");
			ErrorHelper::SetDebug("password", "Mot de passe invalide");
			
			return new RouteRedirectionResponse(self::class, ActionsMenu::LOGIN);
		}

		if (!UserHelper::LogIn($User)) {
			ErrorHelper::SetDefault("connection", "La connection a échoué de manière inatendu (Identifiant et mot de passe corrects)");
			
			return new RouteRedirectionResponse(self::class, ActionsMenu::LOGIN);
		}
		
		$action = DataHelper::Get('saved_action') ?? ActionsMenu::MAIN;
		$controller = DataHelper::Get('saved_controller') ?? self::class;

		switch ($controller) {
			case self::class:
				if ($action == ActionsMenu::LOGIN_PROCESS || $action == ActionsMenu::LOGIN)
					$action = ActionsMenu::MAIN;
				break;
				
			case ControllerConversation::class:
				$controller = ControllerSuiviDemandes::class;
				$action = ActionsSuiviDemandes::LIST_ALL;
				break;
		}

		return new RouteRedirectionResponse($controller, $action);
	}

	/**
	 * LogOut the user and send him back to the logIn page
	 * @return RouteRedirectionResponse
	 */
	public function LogOutProcess(): Response {
		UserHelper::LogOut();
		return new RouteRedirectionResponse(self::class, ActionsMenu::LOGIN);
	}


	/**
	 * Display the main menu of the app
	 * @return HTMLResponse
	 */
	public function MainMenu(): Response {
		return new HTMLResponse(
			"public/Views/menu/main_menu.php"
		);
	}
}