<?php
namespace Core\Session;

use Exception;
use Core\Core;

use Traits\StaticClass;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Models\ModelGroup;
use Models\Entities\User;


/**
 * Handle user data
 * 
 * His connection, disconnection, permissions, inactivity
 * @static
 */
final class UserHelper
{
	use StaticClass;

	/**
	 * List of valid permissions
	 * No user should have a permission not in this list
	 * @var array
	 */
	public static function GetFormPermissions(): array {
		return [
			self::PERMISSIONS_FORM_CDE_CLIENT,
			self::PERMISSIONS_FORM_CDE_FOURNISSEUR,
			self::PERMISSIONS_FORM_DEM_DIVERSES,
			self::PERMISSIONS_FORM_DEM_CONGES,
		];
	}
	
	/**
	 * List of valid permissions
	 * No user should have a permission not in this list
	 * @var array
	 */
	public static function GetSuivisPermissions(): array {
		return [
			self::PERMISSIONS_SUIVIS_MES_DEMANDES,
		];
	}

	/**
	 * List of valid permissions
	 * No user should have a permission not in this list
	 * @var array
	 */
	public static function GetAllPermissions(): array {
		return array_merge(
			self::GetFormPermissions(),
			self::GetSuivisPermissions(),
		);
	}


	public const PERMISSIONS_FORM_CDE_CLIENT = 'web_form_cde_client';
	public const PERMISSIONS_FORM_CDE_FOURNISSEUR = 'web_form_cde_fournisseur';
	public const PERMISSIONS_FORM_DEM_DIVERSES = 'web_form_dem_diverses';
	public const PERMISSIONS_FORM_DEM_CONGES = 'web_form_dem_conges';
	
	public const PERMISSIONS_SUIVIS_MES_DEMANDES = 'web_suivis_mes_demandes';
	
	/**
	 * Time before session timout, reseted when the page is changed
	 * @var int
	 */
	public const USER_TIMEOUT = 1800; // 1800 = 30 min of inactivity


	public static function GetUserId(): ?int {
		return $_SESSION['user']['id'] ?? null;
	}

	public static function GetUser(): ?User {
		return $_SESSION['user']['user_ref'] ?? null;
	}

	public static function GetPermission(): array {
		return $_SESSION['user']['permission'] ?? [];
	}


	public static function Init(): bool {
		if (!isset($_SESSION)) {
			session_start();
		}

		if (self::isLoggedIn()) {
			self::actualisePermission();
		}

		return true;
	}


	/**
	 * @param string[] $permission
	 * @return bool
	 */
	public static function hasPermission(string ...$permission): bool {
		if (!self::isLoggedIn()) {
			return false;
		}

		return self::hasAnyPermission($permission);
	}


	/**
	 * @param string[] $permissions
	 * @return bool
	 */
	public static function hasAnyPermission(array $permissions): bool {
		if (!self::isLoggedIn()) {
			return false;
		}

		foreach($permissions as $permission) {
			if (array_search($permission, self::GetPermission())) {
				return true;
			}
		}

		return false;
	}


	private static function SetPermission(array $permission): bool {
		if (!self::IsLoggedIn()) {
			return false;
		}
		
		$permission = array_intersect($permission, self::GetAllPermissions());

		if ($permission === []) {
			self::logOut();
			ErrorHelper::set('permission', 'Vous n\'avez pas les droits d\'accès aux formulaires');
			Core::Redirect(ActionsMenu::LOGIN, ControllerMenu::class);
			return true;
		}

		$_SESSION['user']['permission'] = $permission;
		return true;
	}

	private static function ActualisePermission(): bool {
		if (!self::isLoggedIn()) {
			return false;
		}

		// Get permissions (group)
		try
		{
			$Groups = ModelGroup::GetInstance()->GetByUserLogin($_SESSION['user']['login']);
		}
		catch (Exception $e) 		{
			ErrorHelper::Set('permission', 'Impossible de récupérer les permissions liés à l\'utilisateur');
			Core::Redirect(ActionsMenu::LOGIN, ControllerMenu::class);
			return false;
		}
		
		$permissions = [];
		foreach ($Groups as $Group) {
			$permissions[] = $Group->description;
		}

		self::SetPermission($permissions);
		return true;
	}

	public static function LogIn(User $User): bool {
		if (!isset($_SESSION)) {
			session_start();
		}

		if (self::isLoggedIn()) {
			self::LogOut();
		}

		
		
		// Save user infos
		unset($User->pass);

		$_SESSION['user'] = [
			'user_ref' => $User, // logged user as an entity
			'id' => $User->id,
			'login' => $User->login,
			'last_activity' => time(),
		];

		self::actualisePermission();

		return true;
	}

	/**
	 * LogOut the user by unsetting the user in the session
	 * @return bool
	 */
	public static function LogOut(): bool {
		unset($_SESSION['user']);
		return true;
	}

	/**
	 * Check if the user is logged in the app
	 * @return bool true if user is saved in the session
	 */
	public static function isLoggedIn(): bool {
		return isset($_SESSION['user']);
	}

	/**
	 * Check if the user is logging in the app
	 * @return bool true if the action and controllers correspond to the act of logging in
	 */
	public static function IsLoggingIn(): bool {
		if (Core::$controller != ControllerMenu::class) {
			return false;
		} 

		if (Core::$action != ActionsMenu::LOGIN && Core::$action != ActionsMenu::LOGIN_PROCESS) {
			return false;
		}

		return true;
	}

	public static function IsSessionTimedOut(): bool {
		return time() - $_SESSION['user']['last_activity'] > self::USER_TIMEOUT;
	}
}