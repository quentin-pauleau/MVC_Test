<?php
namespace Core\Session;

use Traits\StaticClass;


/**
 * Handle error data, by storing them in the $_SESSION ($_SESSION["error"])
 * 
 * @static
 */
final class ErrorHelper
{
	use StaticClass;

	public const TYPE_DEFAULT = 'default';
	public const TYPE_DEBUG = 'debug';

	public static function Init(): bool {
		if (!isset($_SESSION)) {
			session_start();
		}

		if (!isset($_SESSION["error"])) {
			$_SESSION["error"] = [];
		}

		if (!isset($_SESSION["error"][self::TYPE_DEFAULT])) {
			$_SESSION["error"][self::TYPE_DEFAULT] = [];
		}
		
		if (!isset($_SESSION["error"][self::TYPE_DEBUG])) {
			$_SESSION["error"][self::TYPE_DEBUG] = [];
		}

		return true;
	}

	/**
	 * Summary of Set
	 * @param mixed $name
	 * @param string $value
	 * @param string $type
	 * @return bool
	 */
	public static function Set(string $name, string $value, string $type = self::TYPE_DEFAULT): bool {
		if (!isset($_SESSION["error"][$type])) {
			$_SESSION["error"][$type] = [];
		}

		$_SESSION["error"][$type][$name] = $value;
		return true;
	}

	/**
	 * Summary of Set
	 * @param mixed $name
	 * @param string $value
	 * @return bool
	 */
	public static function SetDefault(string $name, string $value): bool {
		return self::Set($name, $value, self::TYPE_DEFAULT);
	}


	/**
	 * Summary of Set
	 * @param mixed $name
	 * @param string $value
	 * @return bool
	 */
	public static function SetDebug(string $name, string $value): bool {
		return self::Set($name, $value, self::TYPE_DEBUG);
	}


	public static function Get(string $name, string $type = self::TYPE_DEFAULT) {
		return $_SESSION["error"][$type][$name] ?? null;
	}

	public static function GetAll(string $type = self::TYPE_DEFAULT): array {
		if (!isset($_SESSION["error"][$type])) {
			return [];
		}

		return array_merge([], $_SESSION["error"][$type]);
	}


	public static function HasError(string $type = self::TYPE_DEFAULT): bool {
		if (!isset($_SESSION["error"][$type])) {
			return false;
		}

		return $_SESSION["error"][$type] != [];
	}


	public static function IsSet(string $name, string $type = self::TYPE_DEFAULT): bool {
		return isset($_SESSION["error"][$type][$name]);
	}


	public static function Clear(?string $type = null): bool {
		if ($type !== null) {
			if (!isset($_SESSION["error"][$type])) {
				return false;
			}

			$_SESSION["error"][$type] = [];
			return true;
		}
		
		$_SESSION["error"] = [];
		return true;
	}
}