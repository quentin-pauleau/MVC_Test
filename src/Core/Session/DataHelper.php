<?php
namespace Core\Session;

use Traits\StaticClass;

/**
 * Handle error data, by storing them in the $_SESSION ($_SESSION["error"])
 * 
 * @static
 */
final class DataHelper
{
	use StaticClass;

	public static function Init(): bool {
		if (!isset($_SESSION)) {
			session_start();
		}

		if (isset($_SESSION["data"])) {
			return true;
		}

		$_SESSION["data"] = [];
		return true;
	}


	public static function Set($name, $value): bool {
		$_SESSION["data"][$name] = $value;
		return true;
	}


	public static function Get($name) {
		return $_SESSION["data"][$name] ?? null;
	}


	public static function GetAll(): array {
		return $_SESSION["data"] ?? [];
	}

	public static function HasData(): bool {
		return $_SESSION["data"] != [];
	}
	

	public static function IsSet($name): bool {
		return isset($_SESSION["data"][$name]);
	}

	public static function UnSet($name): bool {
		if (!self::IsSet($name)) {
			return true;
		}
		
		unset($_SESSION["data"][$name]);
		return true;
	}

	public static function Clear(): bool {
		$_SESSION["data"] = [];
		return true;
	}
}